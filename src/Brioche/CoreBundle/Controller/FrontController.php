<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FrontController extends Controller
{
    /**
     * @Route(
     *      "/",
     *      name = "front_index"
     * )
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * @Route(
     *      "/produits-allergies",
     *      name = "front_products"
     * )
     * @Template()
     */
    public function productsAction()
    {
        return array();
    }
}
