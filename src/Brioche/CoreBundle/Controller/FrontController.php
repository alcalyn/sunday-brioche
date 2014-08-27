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
        $em = $this->getDoctrine()->getManager();
        $roundRepository = $em->getRepository('BriocheCoreBundle:Round');
        $rounds = $roundRepository->findFuturesRounds();
        
        $mail = $this->get('brioche_core.mail_factory')->createTestMail();
        $mail->setTo(array('doubjulien@hotmail.fr' => 'Juju'));
        
        $this->get('mailer')->send($mail);
        
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
