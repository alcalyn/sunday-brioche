<?php

namespace Brioche\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', 'text', array('label' => 'Prénom'))
            ->add('last_name', 'text', array('label' => 'Nom'))
            ->add('address', 'textarea', array('label' => 'Adresse'))
            ->add('city', 'entity', array(
                'label' => 'Ville',
                'class' => 'BriocheCoreBundle:City',
                'attr' => array(
                    'class' => 'select2-with-search'
                ),
            ))
            ->add('email', 'email', array('label' => 'Email'))
            ->add('Valider', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brioche\CoreBundle\Entity\Client'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brioche_corebundle_client';
    }
}
