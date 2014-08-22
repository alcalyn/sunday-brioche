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

class BriocheController extends Controller
{
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
            
            return $this->redirect($this->generateUrl('brioche_size'));
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
            
            return $this->redirect($this->generateUrl('brioche_personalize'));
        }
        
        return array(
            'sizes'     => $sizes,
            'brioche'   => $brioche,
        );
    }

    /**
     * @Route(
     *      "/brioche/touche-personnelle",
     *      name="brioche_personalize"
     * )
     * @Template()
     */
    public function personalizeAction(Request $request)
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
            
            return $this->redirect($this->generateUrl('brioche_address'));
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
        }
        
        return array(
            'brioche'       => $brioche,
            'clientForm'    => $clientForm->createView(),
        );
    }
    
    /**
     * @return BriocheBuilder
     */
    private function getBriocheBuilder()
    {
        return $this->get('brioche_core.brioche_builder');
    }
}
