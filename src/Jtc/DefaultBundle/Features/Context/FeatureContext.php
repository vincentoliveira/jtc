<?php

namespace Jtc\DefaultBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\Step;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
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
        $steps[] = new Step\Then('I should see "Logged in as"');
        
        return $steps;
    }
}
