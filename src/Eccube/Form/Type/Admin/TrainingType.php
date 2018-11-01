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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TrainingType.
 */
class TrainingType extends AbstractType
{
    /**
     * @var Application
     */
    public $app;

    /**
     * TrainingType constructor.
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
        /**
         * @var ArrayCollection $arrCategory array of category
         */
        $arrCategory = $this->app['eccube.repository.category']->getList(null, true);

        $builder
            // 講習会情報
            ->add('product_training', 'product_training', array(
                'mapped' => false,
            ))
            // 商品規格情報
            ->add('class', 'admin_product_class', array(
                'mapped' => false,
            ))
            ->add('description_detail', 'textarea', array(
                'label' => '内容',
            ))
            // 基本情報
            ->add('name', 'text', array(
                'label' => '商品名',
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            // 詳細な説明
            ->add('Tag', 'tag', array(
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
            ))
            ->add('search_word', 'textarea', array(
                'label' => "検索ワード",
                'required' => false,
            ))
            // サブ情報
            ->add('free_area', 'textarea', array(
                'label' => 'サブ情報',
                'required' => false,
            ))

            // 右ブロック
            ->add('Status', 'disp', array(
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('note', 'textarea', array(
                'label' => 'ショップ用メモ帳',
                'required' => false,
            ))

            // タグ
            ->add('tags', 'collection', array(
                'type' => 'hidden',
                'prototype' => true,
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_training';
    }
}
