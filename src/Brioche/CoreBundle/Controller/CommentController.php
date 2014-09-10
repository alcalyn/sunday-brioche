<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Brioche\CoreBundle\Entity\Comment;
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
        $lastModified = $comments[0]->getDateCreate();
        $response = new Response();
        $response
            ->setLastModified($lastModified)
            ->setPublic()
        ;
        
        if ($response->isNotModified($request)) {
            return $response;
        }
        
        $comment = new Comment();
        $brioche = $this->get('brioche_core.brioche_builder')->getCurrentBrioche();
        $comment->setAuthor($brioche->getClient()->getFullName());
        $commentForm = $this->createForm(new CommentType(), $comment);
        
        $commentForm->handleRequest($request);
        
        if ($commentForm->isValid()) {
            if (0 === strlen(trim($comment->getAuthor()))) {
                $comment->setAuthor('Quelqu\'un');
            }
            
            $em->persist($comment);
            $em->flush();
            
            return $this->redirect($this->generateUrl('comment_index'));
        }
        
        return $this->render('BriocheCoreBundle:Comment:index.html.twig', array(
            'comments' => $comments,
            'commentForm' => $commentForm->createView(),
        ), $response);
    }
}
