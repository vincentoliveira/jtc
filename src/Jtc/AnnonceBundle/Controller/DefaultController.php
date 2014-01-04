<?php

namespace Jtc\AnnonceBundle\Controller;

use Jtc\DefaultBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Session\Session;
use Jtc\AnnonceBundle\Entity\Annonce;
use Jtc\AnnonceBundle\Form\AnnonceContactType;

class DefaultController extends BaseController
{
    /**
     * Homepage
     * 
     * @Template
     */
    
    public $transports = array(
        1 => array('id' => 1, 'label' => 'jtc.transport.voiture'),
        2 => array('id' => 2, 'label' => 'jtc.transport.train'),
        3 => array('id' => 3, 'label' => 'jtc.transport.avion')
    );
    
    public $colis = array(
        1 => array('id' => 1, 'label' => 'jtc.colis.documents'),
        2 => array('id' => 2, 'label' => 'jtc.colis.vetements')
    );
      
    public function indexAction()
    {
        $session = $this->getRequest()->getSession();
        $drafContentId = $session->get('draf_content_id');
        if (isset($drafContentId)) {
            $session->set('$drafContentId', null);
            return $this->redirectToRoute('jtc_annonce_complete', array('id' => $drafContentId));
        }
        
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
            'transports' => $this->transports,
            'colis' => $this->colis,
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
        $session = $this->getRequest()->getSession();
        $session->set('$drafContentId', null);
            
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
     * Edition d'une annonce
     * 
     * @param \Jtc\AnnonceBundle\Entity\Annonce $annonce
     * @Template
     * @ParamConverter("annonce", class="Jtc\AnnonceBundle\Entity\Annonce", options={"id"="id"})
     */
    public function editAction(Annonce $annonce)
    {
        $user = $this->getUser();
        
        if ($user->getId() != $annonce->getUtilisateur()->getId()) {
            return $this->redirectToRoute('jtc_annonce_show', array('id' => $annonce->getId()));
        }
        
        $errors = array();
        
        $request = $this->getRequest();
        $postData = $request->request->all();
        if ($request->getMethod() == "POST") {
            $formHandler = $this->get('jtc_annonce.annonce_service');

            $isValid = $formHandler->isValid($postData, true);
            if ($isValid === true) {
                $annonceId = $formHandler->editAnnonce($annonce, $postData);
                if ($annonceId === false) {
                    $errors['internal'][] = 'internal';
                } else {
                    return $this->redirectToRoute('jtc_annonce_show', array('id' => $annonceId));
                }
            } else {
                $errors = $isValid;
            }
        }
        
        return array(
            'annonce' => $annonce,
            'errors' => $errors,
            'transports' => $this->transports,
            'colis' => $this->colis
        );
    }
    
    /**
     * Edition d'une annonce
     * 
     * @param \Jtc\AnnonceBundle\Entity\Annonce $annonce
     * @Template
     * @ParamConverter("annonce", class="Jtc\AnnonceBundle\Entity\Annonce", options={"id"="id"})
     */
    public function deleteAction(Annonce $annonce)
    {
        $user = $this->getUser();
        
        if ($user->getId() != $annonce->getUtilisateur()->getId()) {
            return $this->redirectToRoute('jtc_annonce_show', array('id' => $annonce->getId()));
        }
        
        $statuts = $this->container->getParameter('annonce.status');
        $statut = $statuts['supprime'];
        $annonce->setStatut($statut);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($annonce);
        $em->flush();
        
        return $this->redirectToRoute('jtc_annonce_mes_annonces');
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
        return array('annonce' => $annonce,            
            'transports' => $this->transports,
            'colis' => $this->colis,);
    }

    /**
     * Mes annonces
     * 
     * @Template()
     */
    public function mesAnnoncesAction()
    {
        $service = $this->get('jtc_annonce.annonce_service');
        $user = $this->getUser();
        $annonces = $service->getAnnoncesFromUser($user->getId());
        
        return array('annonces' => $annonces);
    }
    
    /**
     * Liste des annonces 'voyageurs'
     * 
     * @Template()
     */
    public function voyageursAction()
    {
        $twig = $this->container->get('twig');
        $globals = $twig->getGlobals();
        $repo = $this->getRepository('JtcAnnonceBundle:Annonce');
        $annonces = $repo->getLastAnnonce($globals['annonce_type']['voyageur']);
        return array('annonces' => $annonces);
    }
    
    /**
     * Liste des annonces 'expediteur'
     * 
     * @Template()
     */
    public function expediteursAction()
    {
        $twig = $this->container->get('twig');
        $globals = $twig->getGlobals();
        $repo = $this->getRepository('JtcAnnonceBundle:Annonce');
        $annonces = $repo->getLastAnnonce($globals['annonce_type']['expediteur']);
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
        $twig = $this->container->get('twig');
        $globals = $twig->getGlobals();
        
        $pageToGoBackTo = ($type == $globals['annonce_type']['voyageur']) ? 'JtcAnnonceBundle:Default:voyageurs.html.twig' : 'JtcAnnonceBundle:Default:expediteurs.html.twig';
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
    
    public function contactAction($id) 
    {
        $em = $this->getDoctrine()->getManager();
        $aRepository = $em->getRepository('JtcAnnonceBundle:Annonce');

        $annonce = $aRepository->find($id);


        $form = $this->createForm(new AnnonceContactType);
        $request = $this->container->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $mailer = $this->container->get('mailer');
                $message = \Swift_Message::newInstance();
                $data = $form->getData();
                $mailContent = $this->render(
                        'JtcAnnonceBundle:Default:email.html.twig', array('contenu' => $data['contenu'],
                    'titre' => $data['sujet'], 'email' => $data['email'])
                );
                $formHandler = $this->get('jtc_annonce.annonce_service');
                $uRepository = $em->getRepository('JtcUserBundle:User');
                $email = $uRepository->find($annonce->getUtilisateur())->getEmail();
                $sujet = $formHandler->truncate($annonce->getDescription(), 20, '...', true);
                $message->setSubject("Nouveau message sur votre annonce : " . $sujet)
                        ->setFrom($data['email'])
                        ->setTo($email)
                        ->setContentType('text/html')
                        ->setBody($mailContent);

                $mailer->send($message);

                // On redirige vers la page de visualisation de l'article nouvellement créé
                $this->get('session')->getFlashBag()->add('info', 'Message bien envoyé');
                return $this->render('JtcAnnonceBundle:Default:show.html.twig', array(
                            'annonce' => $annonce
                        ));
            }
        }
        // On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('JtcAnnonceBundle:Default:contact.html.twig', array(
                    'form' => $form->createView(),
                    'annonce' => $annonce
                ));
    }
}
