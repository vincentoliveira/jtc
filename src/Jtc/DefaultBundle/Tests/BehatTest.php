<?php

namespace Sharimg\DefaultBundle\Tests\Controller;

use Behat\Behat\Console\BehatApplication;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests BeHat
 */
class BehatTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Setup
     */
    protected function setup()
    {
        $this->client = static::createClient(array('environment' => 'test'));
        $this->client->followRedirects();
    }

    /**
     * @test
     * @group Stories
     */
    public function scenariosMeetAcceptanceCriteria()
    {
        $input = new ArrayInput(array(
                    'features' => '@JtcDefaultBundle'
                 ));

        $output = new ConsoleOutput();
        $app = new BehatApplication('DEV');
        $app->setAutoExit(false);

        $result = $app->run($input, $output);
        $this->assertEquals(0, $result, 'At least one Behat test don\'t pass.');    }
}
