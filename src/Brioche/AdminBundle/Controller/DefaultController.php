<?php

namespace Brioche\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brioche\AdminBundle\Form\Type\MailType;

class DefaultController extends Controller
{
    /**
     * @Route(
     *      "/admin",
     *      name = "admin_index"
     * )
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'rounds' => $this->findAllRounds(),
            'mails' => $this->findAllMails(),
            'comments' => $this->findAllComments(),
            'mailForm' => $this->getMailForm()->createView(),
        );
    }
    
    /**
     * @Route(
     *      "/admin/send-mail",
     *      name = "admin_send_mail"
     * )
     */
    public function sendMailAction(Request $request)
    {
        $mailForm = $this->getMailForm();
        $flashMsg = $this->get('brioche_core.flash_messages');
        
        $mailForm->handleRequest($request);
        
        if ($mailForm->isValid()) {
            $mail = $mailForm->getData();
            
            try {
                $this->get('brioche_core.mail_factory')->sendSimpleMail($mail->getTo(), $mail->getContent());
                
                $flashMsg->addSuccess('Mail envoyé à '.$mail->getTo());
            } catch (\Exception $e) {
                $flashMsg->addDanger('Erreur lors de l\'envoi du mail : "'.$e->getMessage().'"');
            }
        } else {
            $flashMsg->addDanger('Erreur dans le formulaire d\'envoi');
        }
        
        return $this->redirect($this->generateUrl('admin_index'));
    }
    
    private function findAllRounds()
    {
        $rounds = $this->getDoctrine()->getRepository('BriocheCoreBundle:Round')->createQueryBuilder('r')
                ->select('r, b, s, e, c, city')
                ->leftJoin('r.brioches', 'b')
                ->leftJoin('b.size', 's')
                ->leftJoin('b.extra', 'e')
                ->leftJoin('b.client', 'c')
                ->leftJoin('c.city', 'city')
                ->where('r.date > :date')
                ->orderBy('r.date', 'desc')
                ->addOrderBy('b.dateValidate', 'desc')
                ->addOrderBy('b.dateLock', 'desc')
                ->addOrderBy('b.dateCreate', 'desc')
                ->setParameters(array(
                    ':date' => (new \DateTime)->modify('-35 days'),
                ))
                ->getQuery()
                ->getResult()
        ;
        
        foreach ($rounds as $round) {
            $round->isNext = false;
        }
        
        for ($i = count($rounds) - 1; $i >= 0; $i--) {
            if ($rounds[$i]->getDate() >= new \DateTime) {
                $rounds[$i]->isNext = true;
                break;
            }
        }
        
        return $rounds;
    }
    
    private function findAllMails()
    {
        return $this->getDoctrine()->getRepository('BriocheCoreBundle:Mail')->createQueryBuilder('m')
                ->select('m')
                ->orderBy('m.dateCreate', 'desc')
                ->getQuery()
                ->getResult()
        ;
    }
    
    private function findAllComments()
    {
        return $this->getDoctrine()->getRepository('BriocheCoreBundle:Comment')->createQueryBuilder('c')
                ->select('c')
                ->orderBy('c.dateCreate', 'desc')
                ->getQuery()
                ->getResult()
        ;
    }
    
    private function getMailForm()
    {
        return $this->createForm(new MailType(), null, array(
            'action' => $this->generateUrl('admin_send_mail'),
            'method' => 'post',
        ));
    }
}
