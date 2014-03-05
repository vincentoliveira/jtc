<?php

namespace Jtc\AnnonceBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Jtc\AnnonceBundle\Entity\Annonce;

/**
 * AnnonceService
 */
class AnnonceService {

    protected $em;
    protected $container;

    public function __construct(EntityManager $em, Container $container) {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Check if POST $params are valid
     * @param array $params
     * @param boolean $edit
     * @return array erros or true if params are valid
     */
    public function isValid(Array $params, $edit = false) {
        $errors = array();

        $notEmptyFields = array('date_depart', 'ville_depart', 'ville_arrive', 'type_transport', 'type_colis');
        if ($edit === false) {
            $notEmptyFields[] = 'type';
        }
        foreach ($notEmptyFields as $field) {
            $value = $params[$field];
            if (!isset($value) or empty($value)) {
                $errors[$field][] = 'empty';
            }
        }

        if ($edit === false) {
            // check annonce type
            $type = $params['type'];
            $availableTypes = $this->container->getParameter('annonce.type');
            if (!in_array($type, $availableTypes)) {
                $errors['type'][] = 'bad_value';
            }
        }

        // check date_depart
        try {
            $date = $this->datetimeFromString($this->getPostParams($params, 'date_depart'));
            $now = new \DateTime();
            if ($date->getTimestamp() <= $now->getTimestamp()) {
                $errors['date_depart'][] = 'date_past_value';
            }
        } catch (\Exception $e) {
            $errors['date_depart'][] = 'date_bad_format';
        }

        return empty($errors) ? true : $errors;
    }
    
    /**
     * Add content from form
     * @param array $rowContent
     * @param int $utilisateurId;
     * @return int New content id
     */
    public function hydrateEntity(Array $params, $utilisateurId) {
        try {
            $annonce = new Annonce();

            $statuts = $this->container->getParameter('annonce.status');
            $statut = $statuts['brouillon'];

            // utilisateur
            if ($utilisateurId !== null) {
                $utilisateur = $this->em->getRepository('JtcUserBundle:User')->find($utilisateurId);
                if ($utilisateur !== null) {
                    $annonce->setUtilisateur($utilisateur);
                    $statut = $statuts['default'];
                }
            }

            // statut
            $annonce->setStatut($statut);

            // date
            $dateDepart = $this->datetimeFromString($this->getPostParams($params, 'date_depart'));
            $annonce->setDateDepart($dateDepart);

            // type
            $type = ($this->getPostParams($params, 'type') == 'voyageur') ? 0 : 1;
            $annonce->setType($type);
            // formulaire
            $fields = array(
                'setVilleDepart' => 'ville_depart',
                'setVilleArrive' => 'ville_arrive',
                'setDescription' => 'description',
                'setTypeColis' => 'type_colis',
                'setTypeTransport' => 'type_transport',
                'setPoids' => 'poids',
                'setPrix' => 'prix',
            );
            foreach ($fields as $method => $field) {
                if (method_exists($annonce, $method)) {
                    $annonce->{$method}($this->getPostParams($params, $field));
                }
            }

            $this->em->persist($annonce);
            $this->em->flush();

            return $annonce->getId();
        } catch (\Exception $e) {
            if ($this->container->getParameter("kernel.environment") == 'dev') {
                throw $e;
            }
            return false;
        }
    }

    /**
     * Add content from form
     * @param array $rowContent
     * @param int $utilisateurId;
     * @return int New content id
     */
    public function editAnnonce(Annonce $annonce, Array $params) {
        try {
            // date
            $dateDepart = $this->datetimeFromString($this->getPostParams($params, 'date_depart'));
            $annonce->setDateDepart($dateDepart);

            // formulaire
            $fields = array(
                'setVilleDepart' => 'ville_depart',
                'setVilleArrive' => 'ville_arrive',
                'setDescription' => 'description',
                'setPoids' => 'poids',
                'setPrix' => 'prix',
            );
            foreach ($fields as $method => $field) {
                if (method_exists($annonce, $method)) {
                    $annonce->{$method}($this->getPostParams($params, $field));
                }
            }

            $this->em->persist($annonce);
            $this->em->flush();

            return $annonce->getId();
        } catch (\Exception $e) {
            if ($this->container->getParameter("kernel.environment") == 'dev') {
                throw $e;
            }
            return false;
        }
    }

    /**
     * Complete une annonce:
     *  - assigne un utilisateur
     *  - modifie le statut
     * @param int $annonceId
     * @param int $utilisateurId
     * @param int $statutId
     * @return boolean success
     */
    public function completeAnnonce($annonceId, $utilisateurId, $statutId) {
        try {
            // get annonce
            $annonce = $this->em->getRepository('JtcAnnonceBundle:Annonce')->find($annonceId);
            if ($annonce === null) {
                return false;
            }

            // utilisateur
            if ($utilisateurId !== null) {
                $utilisateur = $this->em->getRepository('JtcUserBundle:User')->find($utilisateurId);
                if ($utilisateur !== null) {
                    $annonce->setUtilisateur($utilisateur);
                }
            }

            $annonce->setStatut($statutId);

            $this->em->persist($annonce);
            $this->em->flush();

            return $annonce->getId();
        } catch (\Exception $e) {
            if ($this->container->get('http_kernel')->getEnvironment() == 'dev') {
                throw $e;
            }
            return false;
        }
    }
    
    /**
     * Récupère les annonces d'un utilisateurs
     * 
     * @param int $userId
     * @return array
     */
    public function getAnnoncesFromUser($userId)
    {
        $statuts = $this->container->getParameter('annonce.status');
        $statutId = $statuts['visible'];
            
        $repo = $this->em->getRepository('JtcAnnonceBundle:Annonce');
        $annonces = $repo->getAnnoncesFromUser($userId, $statutId);
        
        return $annonces;
    }

    /**
     * Get POST parameter or default value
     * @param type $params
     * @param type $name
     * @return string
     */
    protected function getPostParams($params, $name, $default = '') {
        return isset($params[$name]) ? $params[$name] : $default;
    }

     /**
     * trunate a string
     * @param type $string
     * @param type $max_length
     * @param $replacement
     * @param $trunc_at_space
     * @return string
     */
    public function truncate($string, $max_length = 30, $replacement = '', $trunc_at_space = false) {
        $max_length -= strlen($replacement);
        $string_length = strlen($string);

        if ($string_length <= $max_length)
            return $string;

        if ($trunc_at_space && ($space_position = strrpos($string, ' ', $max_length - $string_length)))
            $max_length = $space_position;

        return substr_replace($string, $replacement, $max_length);
    }

    /**
     * Create a datetime from a string
     * 
     * @param string $dateStr
     * @return \DateTime
     */
    protected function datetimeFromString($dateStr)
    {
        return \DateTime::createFromFormat('d/m/Y', $dateStr);
    }
}
