<?php

namespace Jtc\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Jtc\DefaultBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jtc\DefaultBundle\Entity\FirstUser;

class DefaultController extends BaseController
{
    /**
     * Test page
     * 
     * @Template
     */
    public function testAction()
    {
        return $this->render('JtcDefaultBundle:Default:test.html.twig');
    }
    
    /**
     * @Route("/presentation", name="jtc_presentation")
     */
    public function presentationAction() 
    {
        $firstUser = new FirstUser();
        $form = $this->createFormBuilder($firstUser)
            ->add('nom', 'text', array('label' => 'Nom'))
            ->add('prenom', 'text', array('label' => 'Prénom'))
            ->add('email', 'email', array('label' => 'Email'))
            ->add('message', 'textarea', array('label' => 'Message'))
            ->add('Nous contacter', 'submit')
            ->getForm();

        $request = $this->container->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $mailer = $this->container->get('mailer');
                $message = \Swift_Message::newInstance();
                $data = $form->getData();
                 $data['nom'];
                 $data['prenom'];
                 $data['email'];
                 $data['message'];
                 $firstUser->setNom($data['nom']);
                 $firstUser->setPrenom($data['prenom']);
                 $firstUser->setEmail($data['email']);
                 $firstUser->setMessage($data['message']);
                // On redirige vers la page de visualisation de l'article nouvellement créé
                $this->get('session')->getFlashBag()->add('info', 'Message bien envoyé');
                return $this->render('JtcDefaultBundle:Default:helloworld.html.twig');
            }
        }
        return $this->render('JtcDefaultBundle:Default:helloworld.html.twig', array('form' => $form->createView()));
    }
}
