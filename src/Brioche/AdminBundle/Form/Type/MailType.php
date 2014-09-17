<?php

namespace Brioche\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class MailType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('to', 'email', array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Email du destinataire',
                    'wrapper_class' => 'col-xs-12',
                ),
            ))
            ->add('content', 'textarea', array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Message...',
                    'wrapper_class' => 'col-xs-12',
                ),
            ))
            ->add('Valider', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brioche\AdminBundle\Form\Entity\Mail',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brioche_adminbundle_mail';
    }
}
