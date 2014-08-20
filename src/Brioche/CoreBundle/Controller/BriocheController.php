<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BriocheController extends Controller
{
    /**
     * @Route("/brioche/type")
     * @Template()
     */
    public function typeAction()
    {
        return array();
    }

    /**
     * @Route("/brioche/taille")
     * @Template()
     */
    public function sizeAction()
    {
        return array();
    }

    /**
     * @Route("/brioche/personalize")
     * @Template()
     */
    public function personalizeAction()
    {
        return array();
    }
}
