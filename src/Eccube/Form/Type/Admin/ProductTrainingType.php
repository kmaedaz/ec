<?php
/*
 * This file is customized file */


namespace Eccube\Form\Type\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Eccube\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProductTrainingType.
 */
class ProductTrainingType extends AbstractType
{
    /**
     * @var Application
     */
    public $app;

    /**
     * ProductType constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // 講習会情報
            ->add('TrainingType', 'training_type', array(
                'label' => '講習会種別',
                'empty_value' => '選択してください',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('place', 'textarea', array(
                'label' => '会場',
            ))
            ->add('training_date_start', 'text', array(
                'label' => '開始日付',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Regex(array('pattern' => '/^[0-9]{4}\/([0][1-9]|[1][0-2])\/([0][1-9]|[1-2][0-9]|[3][0-1]) ([0-1][0-9]|[2][0-3]):[0-5][0-9]$/')),
                ),
            ))
            ->add('training_date_end', 'text', array(
                'label' => '終了日付',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Regex(array('pattern' => '/^[0-9]{4}\/([0][1-9]|[1][0-2])\/([0][1-9]|[1-2][0-9]|[3][0-1]) ([0-1][0-9]|[2][0-3]):[0-5][0-9]$/')),
                ),
            ))
            ->add('place', 'textarea', array(
                'label' => '会場',
            ))
            ->add('zip', 'zip', array(
                'required' => true,
            ))
            ->add('address', 'address', array(
                'required' => true,
            ))
            ->add('target', 'textarea', array(
                'label' => '対象',
            ))
            ->add('purpose', 'textarea', array(
                'label' => '目的',
            ))
            ->add('item', 'textarea', array(
                'label' => '持ち物',
            ))
            ->add('collaborators', 'text', array(
                'label' => '協力',
            ))
            ->add('area', 'text', array(
                'label' => '地域',
            ))
            ->add('accept_limit_date', 'text', array(
                'label' => '申込受付期限',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Regex(array('pattern' => '/^[0-9]{4}\/([0][1-9]|[1][0-2])\/([0][1-9]|[1-2][0-9]|[3][0-1]) ([0-1][0-9]|[2][0-3]):[0-5][0-9]$/')),
                ),
            ))
       ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eccube\Entity\ProductTraining',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'product_training';
    }
}
