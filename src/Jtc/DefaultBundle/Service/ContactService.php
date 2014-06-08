<?php

namespace Jtc\DefaultBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Jtc\DefaultBundle\Entity\FirstUser;

/**
 * ContactService
 */
class ContactService {

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
    public function SendUsMail($data, $emailZecolis, $livredor = false) 
    {
        if ($data->getMessage()) {
            $mailer = $this->container->get('mailer');
            $message = \Swift_Message::newInstance();
            $subject = ($livredor) ? "Nouveau Message dans le livre d'or" : "Nouveau contact sur Zecolis";
            $message->setSubject($subject)
                    ->setFrom($data->getEmail())
                    ->setTo($emailZecolis)
                    ->setContentType('text/html')
                    ->setBody($data->getMessage());
            $mailer->send($message);
        }
         $data->setLivredor($livredor);
         $this->em->persist($data);
         $this->em->flush();
    }
    
    public function getListeAvis() 
    {
        $listAvis = $this->em->getRepository('JtcDefaultBundle:FirstUser')->findBy(array('livredor' => true));
        return $listAvis;
    }
    
    public function getLastAvis() 
    {
        $lastAvis = $this->em->getRepository('JtcDefaultBundle:FirstUser')->getLastAvis(true, 3);
        return $lastAvis;
    }

}

