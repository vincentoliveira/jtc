<?php

namespace Jtc\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Jtc\DefaultBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jtc\DefaultBundle\Entity\FirstUser;

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
     * FAQ
     * 
     *  @Route("/a-propos", name="jtc_propos")
     */
    public function proposAction() {
        return $this->render('JtcDefaultBundle:Default:apropos.html.twig');
    }
    
    /**
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
                if ($data->getMessage()) {
                    $mailer = $this->container->get('mailer');
                    $data = $form->getData();
                    $message = \Swift_Message::newInstance();
                    $message->setSubject("Nouveau contact sur Zecolis ")
                            ->setFrom($data->getEmail())
                            ->setTo($emailZecolis)
                            ->setContentType('text/html')
                            ->setBody($data->getMessage());

                    $mailer->send($message);
                }
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($data);
                $em->flush();
                // On redirige vers la page de visualisation de l'article nouvellement créé
                $this->get('session')->getFlashBag()->add('info', 'Votre message à bien été envoyé');
            }
        }
        return $this->render('JtcDefaultBundle:Default:helloworld.html.twig', array('form' => $form->createView()));
    }

}
