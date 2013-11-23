<?php

namespace Jtc\AnnonceBundle\Controller;

use Jtc\DefaultBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Jtc\AnnonceBundle\Entity\Annonce;

class DefaultController extends BaseController
{
    /**
     * Homepage
     * 
     * @Template
     */
    public function indexAction()
    {
        $repo = $this->getRepository('JtcAnnonceBundle:Annonce');
        $lastAnnonce = $repo->getLastAnnonce(null, 10);
        $todayAnnonce = $repo->getLeavingToday();
        
        return array(
            'lastannonce' => $lastAnnonce,
            'todayannonce' => $todayAnnonce,
        );
    }


    /**
     * Create annonce
     * 
     * @Template
     */
    public function createAction()
    {
        $errors = array();

        $request = $this->getRequest();
        $postData = $request->request->all();
        if ($request->getMethod() == "POST") {
            $formHandler = $this->get('jtc_annonce.annonce_service');

            $isValid = $formHandler->isValid($postData);
            if ($isValid === true) {
                $utilisateur = $this->getUser();
                $annonceId = $formHandler->hydrateEntity($postData, $utilisateur);
                if ($annonceId === false) {
                    $errors['internal'][] = 'internal';
                } else {
                    // si l'utilisateur n'est pas connecté
                    if ($utilisateur === null) {
                        return $this->redirectToRoute('jtc_user_register_before_annonce', array('id' => $annonceId));
                    } else {
                        return $this->redirectToRoute('jtc_annonce_show', array('id' => $annonceId));
                    }
                }
            } else {
                $errors = $isValid;
            }
        }

        return array(
            'errors' => $errors,
            'old_data' => $postData,
        );
    }
    
    
    /**
     * Complete une annonce
     * 
     * @param \Jtc\AnnonceBundle\Entity\Annonce $annonce
     * @ParamConverter("annonce", class="Jtc\AnnonceBundle\Entity\Annonce", options={"id"="id"})
     * @Secure(roles="ROLE_USER")
     */
    public function completeAction(Annonce $annonce)
    {
        $annonceUtilisateur = $annonce->getUtilisateur();
        $lastUpdate = $annonce->getDateMaj();
        $timeToRegisterBeforeAnnonce = $this->container->getParameter('time_to_register_before_annonce');
        $now = new \DateTime();
        
        if ($annonceUtilisateur !== null || ($now->getTimestamp() - $lastUpdate->getTimestamp()) > $timeToRegisterBeforeAnnonce) {
            return $this->redirectToRoute('fos_user_profile_show');
        }
        
        $statuts = $this->container->getParameter('annonce.status');
        $statutId = $statuts['default'];
        $utilisateur = $this->getUser();
        
        $formHandler = $this->get('jtc_annonce.annonce_service');
        $formHandler->completeAnnonce($annonce->getId(), $utilisateur->getId(), $statutId); 
       
        return $this->redirectToRoute('jtc_annonce_show', array('id' => $annonce->getId()));
    }
    
    /**
     * Visualisation d'une annonce
     * 
     * @param \Jtc\AnnonceBundle\Entity\Annonce $annonce
     * @Template
     * @ParamConverter("annonce", class="Jtc\AnnonceBundle\Entity\Annonce", options={"id"="id"})
     */
    public function showAction(Annonce $annonce)
    {
        return array('annonce' => $annonce);
    }

    /**
     * Mes annonces
     * 
     * @Template()
     */
    public function mesAnnoncesAction()
    {
        $repo = $this->getRepository('JtcAnnonceBundle:Annonce');
        $user = $this->getUser();
        $annonces = $repo->getAnnoncesFromUser($user);
        
        return array('annonces' => $annonces);
    }
    
    /**
     * Liste des annonces 'voyageurs'
     * 
     * @Template()
     */
    public function voyageursAction()
    {
        $repo = $this->getRepository('JtcAnnonceBundle:Annonce');
        $annonces = $repo->getLastAnnonce('voyageur');
        return array('annonces' => $annonces);
    }
    
    /**
     * Liste des annonces 'expediteur'
     * 
     * @Template()
     */
    public function expediteursAction()
    {
        $repo = $this->getRepository('JtcAnnonceBundle:Annonce');
        $annonces = $repo->getLastAnnonce('expediteur');
        return array('annonces' => $annonces);
    }
    
    /**
     * Recherche des annonces 'expediteur' ou 'voyageur'
     * 
     * @Template()
     */
    public function searchAction() 
    {
        $request = $this->container->get('request');
        $postData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $aRepository = $em->getRepository('JtcAnnonceBundle:Annonce');

        if (empty($postData)) {
            // rediriger mais ou ? 
        }

        if (!empty($postData)) {
            $type = $postData['type'];
        }
        $pageToGoBackTo = ($type == 'voyageur') ? 'JtcAnnonceBundle:Default:voyageurs.html.twig' : 'JtcAnnonceBundle:Default:expediteurs.html.twig';
        if ($request->getMethod() == 'POST') {
            $qb = $aRepository->doSearch($postData);
            $annonces = $qb->getQuery()->getResult();
            return $this->container->get('templating')->renderResponse($pageToGoBackTo, array(
                        'annonces' => $annonces
                    ));
        }
        $annonces = $aRepository->getLastAnnonce($type);
        return $this->render($pageToGoBackTo, array('annonces' => $annonces
                ));
    }
}
