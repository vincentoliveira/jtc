<?php

namespace Jtc\DefaultBundle\Controller;

use Jtc\DefaultBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends BaseController
{
    /**
     * Test page
     * 
     * @Template
     */
    public function testAction()
    {
        return $this->render('JtcDefaultBundle:Default:test.html.twig');
    }
}
