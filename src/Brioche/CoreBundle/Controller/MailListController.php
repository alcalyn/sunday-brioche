<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MailListController extends Controller
{
    /**
     * @Route(
     *      "/ajouter-mon-email",
     *      name = "maillist_add"
     * )
     * @Method({"POST"})
     */
    public function addPostAction(Request $request)
    {
        $mailListService = $this->get('brioche_core.mail_list');
        
        $mailForm = $mailListService->createForm();
        
        $mailForm->handleRequest($request);
        
        $result = $mailListService->processForm($mailForm, $this->getDoctrine()->getManager());
        
        return new JsonResponse($result);
    }
}
