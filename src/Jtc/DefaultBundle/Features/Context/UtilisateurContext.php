<?php

namespace Jtc\DefaultBundle\Features\Context;

use Behat\Behat\Context\Step;
use Jtc\UserBundle\Entity\User;

/**
 * Utilisateur context.
 */
class UtilisateurContext extends AbstractContext
{
   /**
     * @Given /^je supprime l\'utilisateur "([^"]*)"$/
     */
    public function jeSupprimeLUtilisateur($username)
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('JtcUserBundle:User');
        $user = $repo->findOneByUsername($username);
        
        if ($user !== null) {
            $em->remove($user);
            $em->flush();
        }
    }
    
    /**
     * @Given /^l\'utilisateur "([^"]*)" existe avec le mot de passe "([^"]*)"$/
     */
    public function lUtilisateurExisteAvecLeMotDePasse($username, $password)
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('JtcUserBundle:User');
        $user = $repo->findOneByUsername($username);
        
        if ($user === null) {
            $user = new User();
            $user->setEmail($username.'@test.zecolis.com');
        }
        
        $user->setEnabled(true);
        $user->setUsername($username);
        $user->setPlainPassword($password);
        
        $em->persist($user);
        $em->flush();
    }

    /**
     * @Given /^l\'email "([^"]*)" existe avec le mot de passe "([^"]*)"$/
     */
    public function lEmailExisteAvecLeMotDePasse($email, $password)
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('JtcUserBundle:User');
        $user = $repo->findOneByEmail($email);
        
        if ($user === null) {
            $user = new User();
            $user->setUsername($email);
        }
        
        $user->setEnabled(true);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        
        $em->persist($user);
        $em->flush();
    }

    
    /**
     * @Given /^I am logged as "([^"]*)" password "([^"]*)"$/
     */
    public function iAmLoggedAsPassword($user, $pwd)
    {
        /*
         * And I fill in "_username" with "test"
         */
        $steps[] = new Step\Given('I am on "/login"');
        $steps[] = new Step\Given('I fill in "_username" with "'.$user.'"');
        $steps[] = new Step\Given('I fill in "_password" with "'.$pwd.'"');
        $steps[] = new Step\Given('I press "_submit"');
    }
}
