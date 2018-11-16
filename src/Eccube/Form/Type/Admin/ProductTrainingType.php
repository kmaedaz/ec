<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


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
            ->add('day', 'date', array(
                'label' => '日時',
                'widget' => 'single_text',
                'format' => 'yyyy/MM/dd',
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('time', 'text', array(
                'label' => '時間',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Regex(array('pattern' => '/^^([0-1][0-9]|[2][0-3]):[0-5][0-9]$/')),
                ),
            ))
            ->add('training_date', 'hidden')
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
