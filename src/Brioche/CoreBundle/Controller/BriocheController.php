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
use Brioche\CoreBundle\Form\Type\BriocheTimeType;
use Brioche\CoreBundle\Entity\Brioche;

class BriocheController extends Controller
{
    /**
     * @Route(
     *      "/ma-brioche",
     *      name = "brioche_index"
     * )
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirectNextStep();
    }
    
    /**
     * @Route(
     *      "/ma-brioche/tournee",
     *      name = "brioche_round"
     * )
     * @Template()
     */
    public function roundAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($brioche->getLocked()) {
            return $this->redirect($this->generateUrl('brioche_summary'));
        }
        
        $roundRepository = $em->getRepository('BriocheCoreBundle:Round');
        $rounds = $roundRepository->findFuturesRounds();
        
        if ($request->isMethod('post')) {
            $round = $roundRepository->findOneBy(array(
                'id' => $request->get('round'),
            ));
            
            $this->getBriocheBuilder()->buildRound($round);
            
            return $this->redirectNextStep('round');
        }
        
        $mailForm = $this->get('brioche_core.mail_list')->createForm($this->generateUrl('maillist_add'));
        
        return array(
            'brioche'   => $brioche,
            'rounds'    => $rounds,
            'mailForm' => $mailForm->createView(),
        );
    }
    
    /**
     * @Route(
     *      "/ma-brioche/type",
     *      name = "brioche_type"
     * )
     * @Template()
     */
    public function typeAction(Request $request)
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($brioche->getLocked()) {
            return $this->redirect($this->generateUrl('brioche_summary'));
        }
        
        if ($request->isMethod('post')) {
            $this->getBriocheBuilder()->buildType($request->get('type'));
            
            return $this->redirectNextStep('type');
        }
        
        return array(
            'brioche' => $brioche,
        );
    }

    /**
     * @Route(
     *      "/ma-brioche/taille",
     *      name = "brioche_size"
     * )
     * @Template()
     */
    public function sizeAction(Request $request)
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($brioche->getLocked()) {
            return $this->redirect($this->generateUrl('brioche_summary'));
        }
        
        $sizeRepository = $this->getDoctrine()->getManager()->getRepository('BriocheCoreBundle:Size');
        $sizes = $sizeRepository->findAll();
        
        if ($request->isMethod('post')) {
            $size = $sizeRepository->findOneBy(array(
                'value' => $request->get('size'),
            ));
            
            $this->getBriocheBuilder()->buildSize($size);
            
            return $this->redirectNextStep('size');
        }
        
        return array(
            'sizes'     => $sizes,
            'brioche'   => $brioche,
        );
    }

    /**
     * @Route(
     *      "/ma-brioche/touche-personnelle",
     *      name="brioche_perso"
     * )
     * @Template()
     */
    public function persoAction(Request $request)
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($brioche->getLocked()) {
            return $this->redirect($this->generateUrl('brioche_summary'));
        }
        
        $extraRepository = $this->getDoctrine()->getManager()->getRepository('BriocheCoreBundle:Extra');
        $extras = $extraRepository->findBy(array(), array('price' => 'asc'));
        
        if ($request->isMethod('post')) {
            $butter = $request->get('butter');
            $sugar = $request->get('sugar');
            $extra = $extraRepository->findOneBy(array(
                'id' => $request->get('extra'),
            ));
            
            $this->getBriocheBuilder()->buildPerso($butter, $sugar, $extra);
            
            return $this->redirectNextStep('perso');
        }
        
        return array(
            'extras'    => $extras,
            'brioche'   => $brioche,
        );
    }

    /**
     * @Route(
     *      "/ma-brioche/adresse-de-livraison",
     *      name="brioche_address"
     * )
     * @Template()
     */
    public function addressAction(Request $request)
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($brioche->getLocked()) {
            return $this->redirect($this->generateUrl('brioche_summary'));
        }
        
        $client = $brioche->getClient();
        $clientForm = $this->createForm(new ClientType(), $client);
        
        $clientForm->handleRequest($request);
        
        if ($clientForm->isValid()) {
            $brioche->setValidAddress(true);
            
            return $this->redirectNextStep('address');
        }
        
        return array(
            'brioche'       => $brioche,
            'clientForm'    => $clientForm->createView(),
        );
    }

    /**
     * @Route(
     *      "/ma-brioche/heure-de-livraison",
     *      name="brioche_time"
     * )
     * @Template()
     */
    public function timeAction(Request $request)
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($brioche->getLocked()) {
            return $this->redirect($this->generateUrl('brioche_summary'));
        }
        
        $briocheTimeForm = $this->createForm(new BriocheTimeType(), $brioche);
        
        $briocheTimeForm->handleRequest($request);
        
        if ($briocheTimeForm->isValid()) {
            $this->getBriocheBuilder()->buildTime();
            
            return $this->redirectNextStep('time');
        }
        
        return array(
            'brioche' => $brioche,
            'briocheTimeForm' => $briocheTimeForm->createView(),
        );
    }

    /**
     * @Route(
     *      "/ma-brioche/resume-commande",
     *      name="brioche_summary"
     * )
     * @Template()
     */
    public function summaryAction(Request $request)
    {
        $brioche = $this->getBriocheBuilder()->getCurrentBrioche();
        
        if ($brioche->getLocked()) {
            return $this->redirect($this->generateUrl('command_index', array(
                'token' => $brioche->getToken(),
            )));
        }
        
        if ($request->isMethod('post')) {
            if ($request->get('valid')) {
                $this->getBriocheBuilder()->lockBrioche();
                
                $commandUrl = $this->generateUrl('command_index', array(
                    'token' => $brioche->getToken(),
                ), true);
                
                $this->get('brioche_core.mail_factory')->sendBriocheValidatedMail($brioche->getClient(), $commandUrl);
                
                return $this->redirect($commandUrl.'#suivi-commande');
            }
            
            return $this->redirect($this->generateUrl('brioche_summary'));
        }
        
        return array(
            'brioche'   => $brioche,
            'client'    => $brioche->getClient(),
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
     * Return a redirect to the next step of the brioche process
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectNextStep($from = null)
    {
        $step = $this->getBriocheBuilder()->getNextStep($from);
        $path = 'brioche_'.$step;
        
        return $this->redirect($this->generateUrl($path));
    }
}
