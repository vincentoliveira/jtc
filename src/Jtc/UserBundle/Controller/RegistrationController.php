<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jtc\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


/**
 * Extends controller managing the registration
 *
 * @author Vincent Oliveira
 */
class RegistrationController extends BaseController
{
    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction(Request $request, $token)
    {        
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);
        
        $statuts = $this->container->getParameter('annonce.status');
        $statutId = $statuts['brouillon'];
        $repo = $this->container->get('doctrine')->getRepository('JtcAnnonceBundle:Annonce');
        $annonce = $repo->findOneBy(array(
            'statut' => $statutId,
            'utilisateur' => $user->getId(),
        ));
        if ($annonce !== null) {
            $statutId = $statuts['visible'];
            $annonce->setStatut($statutId);
            $em = $this->container->get('doctrine')->getEntityManager();
            $em->persist($annonce);
            $em->flush();
        } elseif (null === $response = $event->getResponse()) {
            $url = $this->container->get('router')->generate('fos_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));
        
        $firewall = $this->container->getParameter('fos_user.firewall_name');
        $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        $this->container->get('security.context')->setToken($token);
        
        return $response;
    }
}
