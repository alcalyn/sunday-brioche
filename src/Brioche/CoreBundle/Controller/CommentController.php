<?php

namespace Brioche\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('BriocheCoreBundle:Comment')->findAllEnabledComments();
        
        $comment = new Comment();
        $brioche = $this->get('brioche_core.brioche_builder')->getCurrentBrioche();
        $comment->setAuthor($brioche->getClient()->getFullName());
        $commentForm = $this->createForm(new CommentType(), $comment);
        
        $commentForm->handleRequest($request);
        
        if ($commentForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment = $commentForm->getData();
            
            if (0 === strlen(trim($comment->getAuthor()))) {
                $comment->setAuthor('Quelqu\'un');
            }
            
            $em->persist($comment);
            $em->flush();
            
            return $this->redirect($this->generateUrl('comment_index'));
        }
        
        return array(
            'comments' => $comments,
            'commentForm' => $commentForm->createView(),
        );
    }
}
