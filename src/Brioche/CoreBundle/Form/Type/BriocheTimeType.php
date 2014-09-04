<?php

namespace Brioche\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BriocheTimeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $timeOptions = array(
            'label' => false,
            'hours' => range(7, 10),
            'minutes' => range(0, 55, 5),
        );
        
        $builder
            ->add('shipTimeMin', 'time', $timeOptions)
            ->add('shipTimeMax', 'time', $timeOptions)
            ->add('Valider', 'submit', array(
                'attr' => array('wrapper_class' => ' '),
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brioche\CoreBundle\Entity\Brioche'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brioche_corebundle_brioche';
    }
}
