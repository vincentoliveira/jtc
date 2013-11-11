<?php

namespace Jtc\AnnonceBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Jtc\AnnonceBundle\Entity\Annonce;

/**
 * AnnonceFormHandler: Handle Annonce form
 */
class AnnonceFormHandler
{
    protected $em;
    protected $container;

    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }
    
    /**
     * Check if POST $params are valid
     * @param array $params
     * @return array erros or true if params are valid
     */
    public function isValid(Array $params)
    {
        $errors = array();
        
        $notEmptyFields = array('type', 'date_depart', 'ville_depart', 'ville_arrive');
        foreach ($notEmptyFields as $field) {
            $value = $params[$field];
            if (!isset($value) or empty($value)) {
                $errors[$field][] = 'empty';
            }
        }
        
        // check annonce type
        $type = $params['type'];
        $availableTypes = $this->container->getParameter('annonce.type');
        if (!in_array($type, $availableTypes)) {
            $errors['type'][] = 'bad_value';            
        }
        
        // check date_depart
        try {
            $date = new \DateTime($this->getPostParams($params, 'date_depart'));
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
     * @return int New content id
     */
    public function hydrateEntity(Array $params)
    {
        try {
            $annonce = new Annonce();
            
            // utilisateur
            $token = $this->container->get('security.context')->getToken();
            $utilisateur = $token !== null ? $token->getUser() : null;
            if (!$utilisateur instanceof \Jtc\UserBundle\Entity\User) {
                return false;
            }
            $annonce->setUtilisateur($utilisateur);
            
            // statut
            $statuts = $this->container->getParameter('annonce.status');
            $defaultStatut = $statuts['default'];
            $annonce->setStatut($defaultStatut);
            
            // date
            $dateDepart = new \DateTime($this->getPostParams($params, 'date_depart'));
            $annonce->setDateDepart($dateDepart);
            
            // formulaire
            $fields = array(
                'setType' => 'type',
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
        }
        catch (\Exception $e) {
            throw $e;
            return false;            
        }
    }
    
    
    /**
     * Get POST parameter or default value
     * @param type $params
     * @param type $name
     * @return string
     */
    protected function getPostParams($params, $name, $default = '')
    {
        return isset($params[$name]) ? $params[$name] : $default;
    }
}
