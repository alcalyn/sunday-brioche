<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CommandController extends Controller
{
    /**
     * @Route(
     *      "/commande/{token}",
     *      name = "command_index"
     * )
     * @Template()
     */
    public function indexAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $brioche = $em->getRepository('BriocheCoreBundle:Brioche')->findOneBy(array(
            'token' => $token,
        ));
        
        $vars = array(
            'brioche'       => $brioche,
            'client'        => $brioche->getClient(),
        );
        
        if (!$brioche->getValidated()) {
            $briochePayment = $this->get('brioche_core.brioche_payment');

            $vars['pay_full_url'] = $briochePayment->getPaymentUrl($brioche, false);
            $vars['pay_half_url'] = $briochePayment->getPaymentUrl($brioche, true);
        }
        
        return $vars;
    }
}
