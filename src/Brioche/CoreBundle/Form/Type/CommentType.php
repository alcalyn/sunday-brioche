<?php

namespace Brioche\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', 'text', array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Votre nom ou pseudo',
                    'wrapper_class' => 'col-xs-12',
                )
            ))
            ->add('content', 'textarea', array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Votre commentaire',
                    'wrapper_class' => 'col-xs-12',
                ),
            ))
            ->add('note', 'number', array(
                'label' => false,
                'attr' => array(
                    'min' => 1,
                    'max' => 7,
                    'step' => 1,
                    'class' => 'extended-rating',
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
            'data_class' => 'Brioche\CoreBundle\Entity\Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brioche_corebundle_comment';
    }
}
