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
