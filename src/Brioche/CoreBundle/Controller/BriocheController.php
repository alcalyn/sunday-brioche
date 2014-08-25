<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Brioche\CoreBundle\Exception\BriocheCoreException;
use Brioche\CoreBundle\Services\BriocheManager;
use Brioche\CoreBundle\Services\BriocheBuilder;
use Brioche\CoreBundle\Form\Type\ClientType;
use Brioche\CoreBundle\Entity\Brioche;

class BriocheController extends Controller
{
    /**
     * @Route(
     *      "/brioche/tournee",
     *      name = "brioche_round"
     * )
     * @Template()
     */
    public function roundAction(Request $request)
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        $roundRepository = $this->getDoctrine()->getManager()->getRepository('BriocheCoreBundle:Round');
        $rounds = $roundRepository->findFuturesRounds();
        
        if ($request->isMethod('post')) {
            $round = $roundRepository->findOneBy(array(
                'id' => $request->get('round'),
            ));
            
            $this->getBriocheBuilder()->buildRound($round);
            
            return $this->redirectNextStep();
        }
        
        return array(
            'brioche'   => $brioche,
            'rounds'    => $rounds,
        );
    }
    
    /**
     * @Route(
     *      "/brioche/type",
     *      name = "brioche_type"
     * )
     * @Template()
     */
    public function typeAction(Request $request)
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($request->isMethod('post')) {
            $this->getBriocheBuilder()->buildType($request->get('type'));
            
            return $this->redirectNextStep();
        }
        
        return array(
            'brioche' => $brioche,
        );
    }

    /**
     * @Route(
     *      "/brioche/taille",
     *      name = "brioche_size"
     * )
     * @Template()
     */
    public function sizeAction(Request $request)
    {
        $sizeRepository = $this->getDoctrine()->getManager()->getRepository('BriocheCoreBundle:Size');
        $sizes = $sizeRepository->findAll();
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($request->isMethod('post')) {
            $size = $sizeRepository->findOneBy(array(
                'value' => $request->get('size'),
            ));
            
            $this->getBriocheBuilder()->buildSize($size);
            
            return $this->redirectNextStep();
        }
        
        return array(
            'sizes'     => $sizes,
            'brioche'   => $brioche,
        );
    }

    /**
     * @Route(
     *      "/brioche/touche-personnelle",
     *      name="brioche_perso"
     * )
     * @Template()
     */
    public function persoAction(Request $request)
    {
        $extraRepository = $this->getDoctrine()->getManager()->getRepository('BriocheCoreBundle:Extra');
        $extras = $extraRepository->findAll();
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($request->isMethod('post')) {
            $butter = $request->get('butter');
            $sugar = $request->get('sugar');
            $extra = $extraRepository->findOneBy(array(
                'id' => $request->get('extra'),
            ));
            
            $this->getBriocheBuilder()->buildPerso($butter, $sugar, $extra);
            
            return $this->redirectNextStep();
        }
        
        return array(
            'extras'    => $extras,
            'brioche'   => $brioche,
        );
    }

    /**
     * @Route(
     *      "/brioche/adresse-de-livraison",
     *      name="brioche_address"
     * )
     * @Template()
     */
    public function addressAction(Request $request)
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        $client = $brioche->getClient();
        $clientForm = $this->createForm(new ClientType, $client);
        
        $clientForm->handleRequest($request);
        
        if ($clientForm->isValid()) {
            $brioche->setValidAddress(true);
            
            return $this->redirectNextStep();
        }
        
        return array(
            'brioche'       => $brioche,
            'clientForm'    => $clientForm->createView(),
        );
    }

    /**
     * @Route(
     *      "/brioche/resume-commande",
     *      name="brioche_summary"
     * )
     * @Template()
     */
    public function summaryAction()
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        $client = $brioche->getClient();
        
        return array(
            'brioche' => $brioche,
            'client' => $client,
        );
    }
    
    /**
     * @return BriocheBuilder
     */
    private function getBriocheBuilder()
    {
        return $this->get('brioche_core.brioche_builder');
    }
    
    /**
     * Return a redirect to the next step of the brioche proccess
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectNextStep()
    {
        $path = null;
        
        switch ($step = $this->getBriocheBuilder()->getNextStep()) {
            default:
                $path = 'brioche_'.$step;
        }
        
        return $this->redirect($this->generateUrl($path));
    }
}
