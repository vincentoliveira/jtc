<?php

namespace Jtc\DefaultBundle\TwigExtension;

use Symfony\Component\DependencyInjection\Container;

/**
 * Jtc Twig Extension
 */
class JtcTwigExtension extends \Twig_Extension
{    
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * Get globals
     * @return array
     */
    public function getGlobals()
    {
        return array(
            'breadcrumb' => $this->breadcrumb(),
        );
    }
    
    /**
     * Generate breadcrumb list
     * @return array
     */
    public function breadcrumb($routeTree = null)
    {
        
        $request = $this->container->get('request');
        $curRoute = $request->get('_route');
        
        if ($routeTree == null) {
            $routeTree = $this->container->getParameter('route_tree');
        }
        
        foreach ($routeTree as $route => $children) {
            if ($route == $curRoute) {
                return array($route);
            }
            if (!empty($children)) {
                $childrenBreadcrumb = $this->breadcrumb($children);
                if (!empty($childrenBreadcrumb)) {
                    $breadcrumb = array();
                    $breadcrumb[0] = $route;
                    foreach ($childrenBreadcrumb as $i => $childRoute) {
                        $breadcrumb[$i + 1] = $childRoute;                        
                    }
                    return $breadcrumb;
                }
            }
        }
        
        return array();
    }
    
    /**
     * Get Name
     * @return string
     */
    public function getName()
    {
        return 'jtc_twig_extension';
    }
}
