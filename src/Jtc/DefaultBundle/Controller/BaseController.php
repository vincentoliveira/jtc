<?php

namespace Jtc\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Base Controller
 */
class BaseController extends Controller
{    
    /**
     * Translate $msg
     * @param string $msg
     * @param array $params
     * @return string translation
     */
    public function trans($msg, $params = array())
    {
        return $this->get('translator')->trans($msg, $params);
    }
    
    /**
     * Get entity repository
     * @param string $persistentObjectName
     * @return Entityrepository
     */
    public function getRepository($persistentObjectName)
    {
        return $this->getDoctrine()->getRepository($persistentObjectName);
    }
    
    /**
     * 
     * @param type $route
     * @param type $parameters
     * @param type $referenceType
     * @param type $status
     * @return type
     */
    public function redirectToRoute($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $status = 302)
    {
        return $this->redirect($this->generateUrl($route, $parameters, $referenceType), $status);
    }
}
