<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Brioche\CoreBundle\Entity\Message;
use Brioche\CoreBundle\Form\Type\MessageType;

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
        $brioche = $em->getRepository('BriocheCoreBundle:Brioche')->findFullByToken($token);
        
        $vars = array(
            'brioche'       => $brioche,
            'client'        => $brioche->getClient(),
            'messageForm'   => $this->createMessageForm($token)->createView(),
        );
        
        if (!$brioche->getValidated()) {
            $briochePayment = $this->get('brioche_core.brioche_payment');

            $vars['pay_full_url'] = $briochePayment->getPaymentUrl($brioche, false);
            $vars['pay_half_url'] = $briochePayment->getPaymentUrl($brioche, true);
        }
        
        return $vars;
    }
    
    /**
     * @Route(
     *      "/commande/{token}/envoi-message",
     *      name = "command_post_message"
     * )
     * @Method({"POST"})
     */
    public function postMessageAction($token, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $brioche = $em->getRepository('BriocheCoreBundle:Brioche')->findOneBy(array(
            'token' => $token,
        ));
        
        if (null === $brioche) {
            throw $this->createNotFoundException();
        }
        
        $messageForm = $this->createMessageForm($token, $message = new Message());
        
        $messageForm->handleRequest($request);
        
        if ($messageForm->isValid()) {
            $message
                ->setBrioche($brioche)
                ->setAuthor(Message::AUTHOR_CLIENT)
            ;
            
            $em->persist($message);
            $em->flush();
        }
        
        return $this->redirect($this->generateUrl('command_index', array(
            'token' => $token,
        )).'#actions');
    }
    
    private function createMessageForm($token, Message $message = null)
    {
        return $this->createForm(new MessageType(), $message, array(
            'action' => $this->generateUrl('command_post_message', array('token' => $token)),
        ));
    }
}
