<?php

namespace Vivait\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class TwoFactorGeneratorType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'two_factor_generator';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add(
                'current_password',
                'password',
                [
                    'constraints' => [
                        new UserPassword(
                            [
                                'message' => "Please enter the correct password"
                            ]
                        )
                    ],
                    'attr' => [
                        'placeholder' => 'Confirm your password',
                    ],
                    'label' => false,
                    'horizontal_input_wrapper_class' => 'col-sm-12 col-md-4'
                ]
            )
            ->add(
                'Generate',
                'submit',
                [
                    'attr' => [
                        'class' => 'btn-flat expanding-input-hidden',
                    ]
                ]
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['attr' => ['class' => 'expands']]);
    }

}
