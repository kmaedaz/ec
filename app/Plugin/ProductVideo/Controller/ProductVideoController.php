<?php

/*
 * This file is part of the ProductVideo
 *
 * Copyright (C) 2018 ふまねっと
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ProductVideo\Controller;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;

class ProductVideoController
{

    /**
     * ProductVideo画面
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request)
    {

        // add code...

        return $app->render('ProductVideo/Resource/template/index.twig', array(
            // add parameter...
        ));
    }

    /**
     * ProductVideo画面
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function history(Application $app, Request $request, $page_no = null)
    {

        // add code...
         $session = $request->getSession();


        $pagination = array();

        $disps       = $app['eccube.repository.master.disp']->findAll();
        $pageMaxis   = $app['eccube.repository.master.page_max']->findAll();
        $page_count  = $app['config']['default_page_count'];
        $page_status = null;
        $active      = false;
		
            if ( is_null( $page_no ) ) {
                // sessionを削除
                $session->remove( 'eccube.admin.productvideo.search' );
                $page_no    = 1;
            } else {
                // pagingなどの処理
                $searchData = $session->get( 'eccube.admin.productvideo.search' );
                if ( ! is_null( $searchData ) ) {
                    // 表示件数
                    $pcount = $request->get( 'page_count' );

                    $page_count = empty( $pcount ) ? $page_count : $pcount;

                    $qb = $app['contact_list.repository.contact_list']->getQueryBuilderBySearchDataForAdmin( $searchData );

                    $pagination = $app['paginator']()->paginate(
                        $qb,
                        $page_no,
                        $page_count
                    );

                    // セッションから検索条件を復元
                    $searchForm->setData( $searchData );
                }
            }

//dump($pagination);

//dump($page_no);
        return $app->render( 'ProductVideo/Resource/template/Mypage/history.twig', array(
		            'pagination'  => $pagination,
		            'page_no'     => "1",
		            'disps'       => $disps,
		            'pageMaxis'   => $pageMaxis,
		            'page_status' => $page_status,
		            'page_count'  => $page_count,
		            'active'      => $active,
        ) );

    }

    /**
     * ProductVideo画面
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function play(Application $app, Request $request)
    {

        // add code...

        return $app->render('ProductVideo/Resource/template/play.twig', array(
            // add parameter...
        ));
    }


}
