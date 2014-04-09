<?php

// src/Acme/UserBundle/Entity/User.php

namespace Jtc\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="jtc_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="telephone", type="string", length=10, nullable=true)
     */
    protected $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255)
     */
    protected $facebookId;
    
        /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    protected $lastname;


    /**
     * Set telephone
     *
     * @param string $telephone
     */
    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone() {
        return $this->telephone;
    }
    
   /**
     * Get friendsList
     *
     * @return string
     */
    public function getFriendsList() {
        return $this->friendsList;
    }
    
            /**
     * Get friendsList
     *
     * @return string
     */
    public function setFriendsList($list) {
        $this->friendList = $list;
    }


    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;
        $this->setUsername($facebookId);
        $this->salt = '';
    }

    /**
     * @return string
     */
    public function getFacebookId() {
        return $this->facebookId;
    }

    /**
     * @return string
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName() {
        return $this->getFirstName() . ' ' . $this->getLastname();
    }

    /**
     * @param Array
     */
    public function setFBData($fbdata) { // C'est dans cette méthode que vous ajouterez vos informations
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->addRole('ROLE_FACEBOOK');
        }
        if (isset($fbdata['first_name'])) {
            $this->setFirstname($fbdata['first_name']);
        }
        if (isset($fbdata['last_name'])) {
            $this->setLastname($fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
         if (isset($fbdata['friend_list'])) {
            $this->setFriendsList($fbdata['friend_list']);
        }
    }

}