<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class StudentType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('firstName', null, array('label' => 'Imię','required' => true))
                ->add('surname', null, array('label' => 'Nazwisko','required' => true))
                ->add('schoolClass', null, array('label' => 'Klasa','required' => true))
                ->add('email', EmailType::class, array('label' => 'Email','required' => false))
                ->add('phone', null, array('label' => 'Telefon','required' => false))
                ->add('address', null, array('label' => 'Adres','required' => false))
                ->add('studentParent', 'AppBundle\Form\StudentParentType', array('label' => 'Rodzic','attr' => array('class' => 'col-sm-6'),'required' => false)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Student'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_student';
    }

}
