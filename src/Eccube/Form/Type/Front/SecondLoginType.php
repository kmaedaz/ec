<?php
/*
 * This file is Customized File.
 */


namespace Eccube\Form\Type\Front;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SecondLoginType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login_menber_id', 'text', array(
                'attr' => array(
                    'max_length' => 19,
                ),
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('login_pin', 'password', array(
                'attr' => array(
                    'max_length' => 8,
                ),
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('_target_path', 'hidden')
            ->add('_form_frame', 'hidden')
            ->add('_form_body', 'hidden')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'second_login';
    }
}
