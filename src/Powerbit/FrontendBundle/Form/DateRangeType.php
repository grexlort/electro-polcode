<?php

namespace Powerbit\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class DateRangeType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
//                ->add('_format', null, array('mapped' => false))
                ->add('startDate', null, array(
                    'mapped' => false,
                    'constraints' => array(
                        new GreaterThanOrEqual(array(
                            'value' => 1388530800,
                            'message' => 'Tylko daty od 1 stycznia 2014'
                                )),
                        new LessThanOrEqual(array(
                            'value' => 1419984000,
                            'message' => 'Tylko daty do 31 grudnia 2014'
                                )),
                        new NotBlank(array(
                            'message' => 'Data nie moze byc pusta'
                                ))
                    )
                        )
                )
                ->add('endDate', null, array(
                    'mapped' => false,
                    'constraints' => array(
                        new GreaterThanOrEqual(array(
                            'value' => 1388530800,
                            'message' => 'Tylko daty od 1 stycznia 2014'
                                )),
                        new LessThanOrEqual(array(
                            'value' => 1419984000,
                            'message' => 'Tylko daty do 31 grudnia 2014'
                                )),
                        new NotBlank(array(
                            'message' => 'Data nie moze byc pusta'
                                ))
                    ))
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'powerbit_frontendbundle_electricitymeterread';
    }

}
