<?php

namespace Jtc\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Jtc\DefaultBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jtc\DefaultBundle\Entity\FirstUser;
use JMS\SecurityExtraBundle\Annotation\Secure;

class DefaultController extends BaseController 
{
    /**
     * FAQ
     * 
     *  @Route("/faq", name="jtc_faq")
     */
    public function faqAction() {
        return $this->render('JtcDefaultBundle:Default:faq.html.twig');
    }
    
     /**
     * A propos
     * 
     *  @Route("/a-propos", name="jtc_propos")
     */
    public function proposAction() {
        return $this->render('JtcDefaultBundle:Default:apropos.html.twig');
    }
    
    /**
     * Presentation
     * 
     * @Route("/presentation", name="jtc_presentation")
     */
    
    public function presentationAction() {
        $emailZecolis = "infos@zecolis.com";
        $firstUser = new FirstUser();
        $form = $this->createFormBuilder($firstUser)
                ->add('nom', 'text', array('label' => 'Nom'))
                ->add('prenom', 'text', array('label' => 'Prénom'))
                ->add('email', 'email', array('label' => 'Email'))
                ->add('message', 'textarea', array('label' => 'Message'))
                ->getForm();

        $request = $this->container->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $contact = $this->get('jtc_default.contact_service');
                $contact->SendUsMail($data, $emailZecolis);
                //$this->get('session')->getFlashBag()->add('info', 'Votre messaDSge à bien été envoyé');
            }
        }
        return $this->render('JtcDefaultBundle:Default:helloworld.html.twig', array('form' => $form->createView()));
    }

    /**
     * Livre d'or
     * 
     * @Route("/livredor", name="jtc_livredor")
     */
    
    public function livreDorAction() {
        $contact = $this->get('jtc_default.contact_service');
        $emailZecolis = "infos@zecolis.com";
        $firstUser = new FirstUser();
        $form2 = $this->createFormBuilder($firstUser)
                ->add('nom', 'text', array('label' => 'Nom'))
                ->add('prenom', 'text', array('label' => 'Prénom'))
                ->add('email', 'email', array('label' => 'Email'))
                ->add('message', 'textarea', array('label' => 'Message'))
                ->getForm();

        $request = $this->container->get('request');

        if ($request->getMethod() == 'POST') {
            $form2->bind($request);
            if ($form2->isValid()) {
                $data = $form2->getData();
                $contact->SendUsMail($data, $emailZecolis, true);
                $this->get('session')->getFlashBag()->add('info', 'Votre message à bien été envoyé');
            }
        }
        
        $listAvis = $contact->getListeAvis();
        return $this->render('JtcDefaultBundle:Default:livredor.html.twig', array('form' => $form2->createView(),
                    'listAvis' => $listAvis));
    }
    
}
