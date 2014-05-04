<?php

// src/Acme/UserBundle/Entity/User.php

namespace Jtc\UserBundle\Security\User\Provider;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use \BaseFacebook;
use \FacebookApiException;

class FacebookProvider implements UserProviderInterface
{

    /**
     * @var \FOS\FacebookBundle\Facebook\FacebookSessionPersistence
     */
    protected $facebook;

    /**
     * @var \FOS\UserBundle\Doctrine\UserManager $userManager
     */
    protected $userManager;

    /**
     * @var \Symfony\Component\Validator\Validator $validator
     */
    protected $validator;

    /**
     * @var \FOS\UserBundle\Security\UserProvider $userProvider
     */
    protected $userProvider;

    public function __construct(BaseFacebook $facebook, $userManager, $validator)
    {
        $this->facebook = $facebook;
        $this->userManager = $userManager;
        $this->validator = $validator;
    }

    public function supportsClass($class)
    {
        return $this->userProvider->supportsClass($class);
    }

    public function findUserByFbId($fbId)
    {
        return $this->userManager->findUserBy(array('facebookId' => $fbId));
    }

    public function loadUserByUsername($username)
    {
        $user = $this->findUserByFbId($username);

        try {
            $fbdata = $this->facebook->api('/me');
        } catch (FacebookApiException $e) {
            $fbdata = null;
        }

        if (!empty($fbdata)) {
            if (empty($user)) {
                $user = $this->userManager->createUser();
                $user->setEnabled(true);
                $user->setPassword('');
            }

            // TODO use http://developers.facebook.com/docs/api/realtime
            $user->setFBData($fbdata);

            if (count($this->validator->validate($user, 'Facebook'))) {
                // TODO: the user was found obviously, but doesnt match our expectations, do something smart
                throw new UsernameNotFoundException('The facebook user could not be stored');
            }
            $this->userManager->updateUser($user);
        }

        if (empty($user)) {
            throw new UsernameNotFoundException('The user is not authenticated on facebook');
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user)) || !$user->getFacebookId()) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getFacebookId());
    }
    
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        $user = $token->getUser();
        if ($user instanceof UserInterface) {
            $this->userChecker->checkPostAuth($user);

            $newToken = new FacebookUserToken($this->providerKey, $user, $user->getRoles(), $token->getAccessToken());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        if (!is_null($token->getAccessToken())) {
              $this->facebook->setAccessToken($token->getAccessToken());
        }

        if ($uid = $this->facebook->getUser()) {
            $newToken = $this->createAuthenticatedToken($uid,$token->getAccessToken());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        throw new AuthenticationException('The Facebook user could not be retrieved from the session.');
    }

}
