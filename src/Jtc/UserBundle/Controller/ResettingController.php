<?php

namespace Jtc\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ResettingController as BaseController;

/**
 * Controller managing the resetting of the password
 */
class ResettingController extends BaseController
{

//    /**
//     * Request reset user password: submit form and send email
//     */
//    public function sendEmailAction(Request $request)
//    {
//        $username = $request->request->get('username');
//        
//        /** @var $user UserInterface */
//        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);
//
//        if (null === $user) {
//            return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:request.html.'.$this->getEngine(), array('invalid_username' => $username));
//        }
//
//        /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
//        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
//        $user->setConfirmationToken($tokenGenerator->generateToken());
//
//        $this->container->get('session')->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
//        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
//        $user->setPasswordRequestedAt(new \DateTime());
//        $this->container->get('fos_user.user_manager')->updateUser($user);
//
//        return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_check_email'));
//    }
}
