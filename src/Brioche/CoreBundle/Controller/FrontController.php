<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Alcalyn\PayplugBundle\Model\Payment;

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
        $em = $this->getDoctrine()->getManager();
        $roundRepository = $em->getRepository('BriocheCoreBundle:Round');
        $rounds = $roundRepository->findFuturesRounds();
        
        return array(
            'rounds' => $rounds,
        );
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
