<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PresenceTypeType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('label', null, array('label' => 'Nazwa'))
                ->add('description', null, array('label' => 'Opis'))
                ->add('countAsAbsence', ChoiceType::class, array('label' => 'Czy liczona jako nieobecna?', 'choices' => array('Tak' => 1, 'Nie' => 0)))
                ->add('forParent', ChoiceType::class, array('label' => 'Czy do wyboru dla rodzica?', 'choices' => array('Tak' => 1, 'Nie' => 0)));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PresenceType'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_presencetype';
    }

}
