<?php
/*
 * This file Customize File
 */

namespace Eccube\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CustomerBasicInfoType extends AbstractType
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $config = $this->config;

        $builder
            ->add('customer_number', 'text', array(
                'label' => '会員番号',
                'required' => false,
                'mapped' => true,
            ))
            ->add('customer_pin_code', 'text', array(
                'label' => 'PINコード',
                'required' => false,
                'mapped' => true,
            ))
            ->add('job', 'text', array(
                'label' => '職業',
                'required' => false,
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => $config['stext_len'],
                    ))
                ),
                'mapped' => true,
            ))
            ->add('instructor_type', 'instructor_type', array(
                'label' => 'インストラクタ',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
                'mapped' => true,
            ))
            ->add('supporter_type', 'supporter_type', array(
                'label' => 'サポーター',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
                'mapped' => true,
            ))
            ->add('status', 'customer_basic_info_status', array(
                'label' => '基本情報ステータス',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
                'mapped' => true,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eccube\Entity\CustomerBasicInfo',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_customer_basic_info';
    }
}
