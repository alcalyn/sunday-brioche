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
        
        if (!$request->isMethod('post')) {
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
        }
        
        $commentForm = $this->createForm(new CommentType());
        
        if ($request->isMethod('post')) {
            $commentForm->handleRequest($request);
            
            if ($commentForm->isValid()) {
                $comment = $commentForm->getData();

                if (0 === strlen(trim($comment->getAuthor()))) {
                    $comment->setAuthor('Quelqu\'un');
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                $this->get('brioche_core.flash_messages')->addSuccess('Commentaire ajouté !');

                return $this->redirect($this->generateUrl('comment_index'));
            } else {
                $this->get('brioche_core.flash_messages')->addDanger('Le formulaire n\'est pas valide.');
            }
        }
        
        return $this->render('BriocheCoreBundle:Comment:index.html.twig', array(
            'comments' => $comments,
            'commentForm' => $commentForm->createView(),
        ), $response);
    }
}
