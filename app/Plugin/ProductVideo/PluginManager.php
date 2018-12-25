<?php

/*
 * This file is part of the ProductVideo
 *
 * Copyright (C) 2018 ふまねっと
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ProductVideo;

use Eccube\Application;
use Eccube\Plugin\AbstractPluginManager;

class PluginManager extends AbstractPluginManager
{

    /**
     * プラグインインストール時の処理
     *
     * @param $config
     * @param Application $app
     * @throws \Exception
     */
    public function install($config, Application $app)
    {
    }

    /**
     * プラグイン削除時の処理
     *
     * @param $config
     * @param Application $app
     */
    public function uninstall($config, Application $app)
    {
    }

    /**
     * プラグイン有効時の処理
     *
     * @param $config
     * @param Application $app
     * @throws \Exception
     */
    public function enable($config, Application $app)
    {
        $em = $app['orm.em'];
        $connection = $em->getConnection();
        $connection->executeUpdate(
            "INSERT INTO  dtb_page_layout (page_id, device_type_id, page_name, url, file_name, edit_flg, create_date, update_date)" .
            "VALUES (NULL, '10',  '動画購入履歴',  'plugin_ProductVideo_history',  '/plugin/productvideo/history/{page_no}', '2', NOW(), NOW())"
        );

        $connection->executeUpdate(
            "INSERT INTO  dtb_page_layout (page_id, device_type_id, page_name, url, file_name, edit_flg, create_date, update_date)" .
            "VALUES (NULL, '10',  '動画閲覧',  'plugin_ProductVideo_play',  '/plugin/productvideo/play/{product_id}', '2', NOW(), NOW())"
        );


    }


    /**
     * プラグイン無効時の処理
     *
     * @param $config
     * @param Application $app
     * @throws \Exception
     */
    public function disable($config, Application $app)
    {
    
        $em = $app['orm.em'];
        $connection = $em->getConnection();
        $connection->executeUpdate("delete from dtb_page_layout where url = 'plugin_ProductVideo_history'");
        $connection->executeUpdate("delete from dtb_page_layout where url = 'plugin_ProductVideo_play'");

        
    }

    /**
     * プラグイン更新時の処理
     *
     * @param $config
     * @param Application $app
     * @throws \Exception
     */
    public function update($config, Application $app)
    {
    }

}
