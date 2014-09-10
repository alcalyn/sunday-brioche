<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class FrontController extends Controller
{
    /**
     * @Route(
     *      "/",
     *      name = "front_index"
     * )
     * @Cache(smaxage="172800")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * Rounds as fragment to include asyncronously with hinclude
     * 
     * @Route(
     *      "/ajax/rounds",
     *      name = "front_rounds"
     * )
     * @Cache(smaxage="120")
     * @Template()
     */
    public function roundsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $roundRepository = $em->getRepository('BriocheCoreBundle:Round');
        $rounds = $roundRepository->findFuturesRounds();
        $mailForm = $this->get('brioche_core.mail_list')->createForm($this->generateUrl('maillist_add'));
        
        return array(
            'rounds' => $rounds,
            'mailForm' => $mailForm->createView(),
        );
    }
    
    /**
     * @Route(
     *      "/produits-allergies",
     *      name = "front_products"
     * )
     * @Cache(smaxage="172800")
     * @Template()
     */
    public function productsAction()
    {
        return array();
    }
    
    /**
     * @Route(
     *      "/villes-desservies",
     *      name = "front_cities"
     * )
     * @Cache(smaxage="172800")
     * @Template()
     */
    public function citiesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('BriocheCoreBundle:City')->findBy(array(), array('title' => 'asc'));
        
        return array(
            'cities' => $cities,
        );
    }
    
    /**
     * @Route(
     *      "/contact",
     *      name = "front_contact"
     * )
     * @Cache(smaxage="172800")
     * @Template()
     */
    public function contactAction()
    {
        return array();
    }
    
    /**
     * Execute a symfony command
     * and print result
     * 
     * @param string $c
     */
    private function command($c)
    {
        $kernel = $this->container->get('kernel');
        $app = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);

        $input = new \Symfony\Component\Console\Input\StringInput($c);
        $output = new \Symfony\Component\Console\Output\StreamOutput(fopen('php://temp', 'w'));

        $app->doRun($input, $output);

        rewind($output->getStream());
        $response =  stream_get_contents($output->getStream());
		var_dump($response);
    }
}
