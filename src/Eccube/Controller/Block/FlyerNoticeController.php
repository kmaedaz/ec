<?php
/*
 * This file is part of EC-CUBE
 *  Customized file
 */


namespace Eccube\Controller\Block;

use Eccube\Application;

class FlyerNoticeController
{
    public function index(Application $app)
    {
        $NewsList = $app['orm.em']->getRepository('\Eccube\Entity\News')
            ->findBy(
                array(),
                array('rank' => 'DESC')
            );

        return $app->render('Block/flyer_notice.twig', array(
            'NewsList' => $NewsList,
        ));
    }
}
