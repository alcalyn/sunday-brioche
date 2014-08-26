<?php

namespace Brioche\CoreBundle\Twig;

class TwigOnPath extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;
    
    /**
     * Constructor.
     * 
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct($requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            'onpath' => new \Twig_Filter_Method($this, 'onpath'),
        );
    }
    
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
        );
    }
    
    /**
     * Output the string if we are in one of route of arguments.
     * 
     * Example:
     *      onpath('string', 'route_name', 'another_route_name', ...);
     * 
     * output 'string' if we are in route_name or another_route_name, or ...
     * else return empty string.
     * 
     * @return string
     */
    public function onpath()
    {
        $current_route = $this->requestStack->getMasterRequest()->get('_route');
        
        $routes = func_get_args();
        $string = array_shift($routes);
        
        foreach ($routes as $route) {
            if ($route == $current_route) {
                return $string;
            }
        }
        
        return '';
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'twig_onpath';
    }
}
