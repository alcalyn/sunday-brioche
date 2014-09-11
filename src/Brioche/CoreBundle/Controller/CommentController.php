<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Brioche\CoreBundle\Form\Type\CommentType;

class CommentController extends Controller
{
    /**
     * @Route(
     *      "/livre-d-or",
     *      name = "comment_index"
     * )
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('BriocheCoreBundle:Comment')->findAllEnabledComments();
        
        // Cache validation
        $lastModified = (count($comments) > 0) ? $comments[0]->getDateCreate() : new \DateTime('1991-11-09');
        $response = new Response();
        $response
            ->setLastModified($lastModified)
            ->setPublic()
        ;
        
        if ($response->isNotModified($request)) {
            return $response;
        }
        
        $commentForm = $this->createForm(new CommentType(), null, array(
            'action' => $this->generateUrl('comment_post'),
        ));
        
        return $this->render('BriocheCoreBundle:Comment:index.html.twig', array(
            'comments' => $comments,
            'commentForm' => $commentForm->createView(),
        ), $response);
    }
    
    /**
     * @Route(
     *      "/livre-d-or/publier-un-message",
     *      name = "comment_post"
     * )
     * @Method({"POST"})
     */
    public function postAction(Request $request)
    {
        $commentForm = $this->createForm(new CommentType());
        
        $commentForm->handleRequest($request);
        
        if ($commentForm->isValid()) {
            $comment = $commentForm->getData();
            
            if (0 === strlen(trim($comment->getAuthor()))) {
                $comment->setAuthor('Quelqu\'un');
            }
            
            $em->persist($comment);
            $em->flush();
            
            return $this->redirect($this->generateUrl('comment_index'));
        }
    }
}
