<?php

namespace Jtc\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Jtc\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->createUser($manager, 'fabrice', 'fabrice@zecolis.com', 'fabrice');
        $this->createUser($manager, 'jean', 'jean@zecolis.com', 'jean');
        $this->createUser($manager, 'vincent', 'vincent@zecolis.com', 'vincent');
    }
    
    /**
     * {@inheritDoc}
     */
    public function createUser(ObjectManager $manager, $username, $email, $password)
    {
        $user = new User();
        
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        
        $user->setEnabled(true);
        
        $this->addReference($username, $user);

        $manager->persist($user);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}