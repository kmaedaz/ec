<?php
/*
 * This file is Customized File.
 */


namespace Eccube\Controller\Admin\Questionnaire;

use Eccube\Application;
use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\QuestionnaireTag;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Service\CsvExportService;
use Eccube\Util\FormUtil;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class QuestionnaireController extends AbstractController
{
    public function index(Application $app, Request $request, $page_no = null)
    {
        $session = $app['session'];
        $builder = $app['form.factory']
            ->createBuilder('admin_search_questionnaire');
        $searchForm = $builder->getForm();

        $pagination = array();
        $disps = $app['eccube.repository.master.disp']->findAll();
        $pageMaxis = $app['eccube.repository.master.page_max']->findAll();
        // 表示件数は順番で取得する、1.SESSION 2.設定ファイル
        $page_count = $session->get('eccube.admin.questionnaire.search.page_count', $app['config']['default_page_count']);
        // 表示件数
        $page_count_param = $request->get('page_count');
        // 表示件数はURLパラメターから取得する
        if ($page_count_param && is_numeric($page_count_param)) {
            foreach ($pageMaxis as $pageMax) {
                if ($page_count_param == $pageMax->getName()) {
                    $page_count = $pageMax->getName();
                    // 表示件数入力値正し場合はSESSIONに保存する
                    $session->set('eccube.admin.questionnaire.search.page_count', $page_count);
                    break;
                }
            }
        }

        $page_status = null;
        $active = false;

        if ('POST' === $request->getMethod()) {
            $searchForm->handleRequest($request);
            if ($searchForm->isValid()) {
                $searchData = $searchForm->getData();

                // paginator
                $qb = $app['eccube.repository.questionnaire']->getQueryBuilderBySearchDataForAdmin($searchData);
                $page_no = 1;
                $pagination = $app['paginator']()->paginate(
                    $qb,
                    $page_no,
                    $page_count,
                    array('wrap-queries' => true)
                );
                // sessionに検索条件を保持
                $viewData = FormUtil::getViewData($searchForm);
                $session->set('eccube.admin.questionnaire.search', $viewData);
                $session->set('eccube.admin.questionnaire.search.page_no', $page_no);
            }
        } else {
            if (is_null($page_no) && $request->get('resume') != Constant::ENABLED) {
                // sessionを削除
                $session->remove('eccube.admin.questionnaire.search');
                $session->remove('eccube.admin.questionnaire.search.page_no');
                $session->remove('eccube.admin.questionnaire.search.page_count');
            } else {
                // pagingなどの処理
                if (is_null($page_no)) {
                    $page_no = intval($session->get('eccube.admin.questionnaire.search.page_no'));
                } else {
                    $session->set('eccube.admin.questionnaire.search.page_no', $page_no);
                }
                $viewData = $session->get('eccube.admin.questionnaire.search');
                if (!is_null($viewData)) {
                    // 表示件数
                    $page_count = $request->get('page_count', $page_count);
                    $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
                    $session->set('eccube.admin.questionnaire.search', $viewData);
                    $qb = $app['eccube.repository.questionnaire']->getQueryBuilderBySearchDataForAdmin($searchData);
                    $pagination = $app['paginator']()->paginate(
                        $qb,
                        $page_no,
                        $page_count,
                        array('wrap-queries' => true)
                    );
                }
            }
        }

        return $app->render('Questionnaire/index.twig', array(
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'disps' => $disps,
            'pageMaxis' => $pageMaxis,
            'page_no' => $page_no,
            'page_status' => $page_status,
            'page_count' => $page_count,
            'active' => $active,
        ));
    }

    public function addAttachment(Application $app, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException('リクエストが不正です');
        }

        $attachments = $request->files->get('admin_questionnaire');

        $files = array();
        if (count($attachments) > 0) {
            foreach ($attachments as $attachmentFile) {
                foreach ($attachmentFile as $attachment) {
                    //ファイルフォーマット検証
                    $mimeType = $attachment->getMimeType();
                    $extension = $attachment->getClientOriginalExtension();
                    $filename = date('mdHis').uniqid('_').'.'.$extension;
                    $attachment->move($app['config']['file_temp_realdir'], $filename);
                    $files[] = ['save_file' => $filename, 'org_file' => $attachment->getClientOriginalName()];
                }
            }
        }

        return $app->json(array('files' => $files), 200);
    }

    public function edit(Application $app, Request $request, $id = null)
    {
        if (is_null($id)) {
            $Questionnaire = new \Eccube\Entity\Questionnaire();
        } else {
            $Questionnaire = $app['eccube.repository.questionnaire']->find($id);
            if (!$Questionnaire) {
                throw new NotFoundHttpException();
            }
        }
        $builder = $app['form.factory']
            ->createBuilder('admin_questionnaire', $Questionnaire);
        $form = $builder->getForm();

        // ファイルの登録
        $form['attachments']->setData($Questionnaire->getQuestionnaireAttachments());

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                log_info('アンケート登録開始:', array($id));
                $rank_details = $request->get('rank_details');
                $detail_rank_info = [];
                if ($rank_details) {
                    foreach ($rank_details as $rank_detail) {
                        list($index, $rank_val) = explode('//', $rank_detail);
                        $detail_rank_info[(string)($index)] = $rank_val;
                    }
                }
                $rank_choices = $request->get('rank_choices');
                $choice_rank_info = [];
                if ($rank_choices) {
                    foreach ($rank_choices as $rank_choice) {
                        list($index_info_str, $rank_val) = explode('//', $rank_choice);
                        $index_info = explode('_', $index_info_str);
                        $choice_rank_info[(string)($index_info[0])][(string)($index_info[1])] = $rank_val;
                    }
                }

                // 子要素を登録
                $Questionnaire = $form->getData();
                $QuestionnaireDetails = $form->get('QuestionnaireDetails')->getData();
                $idx = 0;
                foreach ($QuestionnaireDetails as $QuestionnaireDetail) {
                    $QuestionnaireDetail->setRank(isset($detail_rank_info[(string)$idx])?$detail_rank_info[(string)$idx]:0)
                                        ->setQuestionnaire($Questionnaire);
                    $choice = 0;
                    foreach ($QuestionnaireDetail['QuestionnaireDetailChoices'] as $QuestionnaireDetailChoice) {
                        $QuestionnaireDetailChoice->setRank(isset($choice_rank_info[(string)$idx][(string)$choice])?$choice_rank_info[(string)$idx][(string)$choice]:0)
                                                ->setQuestionnaireDetail($QuestionnaireDetail);
                        $app['orm.em']->persist($QuestionnaireDetailChoice);
                        ++$choice;
                    }
                    $app['orm.em']->persist($QuestionnaireDetail);
                    ++$idx;
                }

                // 添付ファイルの登録
                $add_attachments = $form->get('add_attachments')->getData();
                $idx = 0;
                foreach ($add_attachments as $add_attachment) {
                    $fileinfo = json_decode($add_attachment, true);
                    $QuestionnaireAttachment = new \Eccube\Entity\QuestionnaireAttachment();
                    $QuestionnaireAttachment
                        ->setFileName($fileinfo['save_file'])
                        ->setLabel($fileinfo['org_file'])
                        ->setQuestionnaire($Questionnaire)
                        ->setRank(isset($detail_rank_info[(string)$idx])?$detail_rank_info[(string)$idx]:0);
                    $Questionnaire->addQuestionnaireAttachment($QuestionnaireAttachment);
                    $app['orm.em']->persist($QuestionnaireAttachment);

                    // 移動
                    $file = new File($app['config']['file_temp_realdir'].'/'.$fileinfo['save_file']);
                    $file->move($app['config']['questionnaire_attachment_save_realdir']);
                    ++$idx;
                }

                // 添付ファイルの削除
                $delete_attachments = $form->get('delete_attachments')->getData();
                foreach ($delete_attachments as $delete_attachment) {
                    $fileinfo = json_decode($delete_attachment, true);
                    $QuestionnaireAttachment = $app['eccube.repository.questionnaire_attachment']
                        ->findOneBy(array('file_name' => $fileinfo['save_file']));

                    // 追加してすぐに削除した添付ファイルは、Entityに追加されない
                    if ($QuestionnaireAttachment instanceof \Eccube\Entity\QuestionnaireAttachment) {
                        $Questionnaire->removeQuestionnaireAttachment($QuestionnaireAttachment);
                        $app['orm.em']->remove($QuestionnaireAttachment);

                    }
                    // 削除
                    if (!empty($delete_attachment)) {
                        $fs = new Filesystem();
                        $fs->remove($app['config']['questionnaire_attachment_save_realdir'].'/'.$fileinfo['save_file']);
                    }
                }

                $rank_files = $request->get('rank_files');
                $rank_file_info = [];
                if ($rank_files) {
                    foreach ($rank_files as $rank_file) {
                        list($fileinfo_json, $rank_val) = explode('//', $rank_file);
                        $fileinfo = json_decode($fileinfo_json, true);
                        $rank_file_info[$fileinfo['save_file']] = $rank_val;
                    }
                }
                $rank_files = $request->get('rank_files');
                if ($rank_files) {
                    foreach ($rank_files as $rank_file) {
                        list($fileinfo_json, $rank_val) = explode('//', $rank_file);
                        $fileinfo = json_decode($fileinfo_json, true);
                        $QuestionnaireAttachment = $app['eccube.repository.questionnaire_attachment']
                            ->findOneBy(array(
                                'file_name' => $fileinfo['save_file'],
                                'Questionnaire' => $Questionnaire,
                            ));
                        $QuestionnaireAttachment->setRank($rank_val);
                        $app['orm.em']->persist($QuestionnaireAttachment);
                    }
                }

                $Questionnaire->setUpdateDate(new \DateTime());
                $app['orm.em']->persist($Questionnaire);
                $app['orm.em']->flush();

                log_info('アンケート登録完了', array($id));
                $app->addSuccess('admin.register.complete', 'admin');

                return $app->redirect($app->url('admin_questionnaire_edit', array('id' => $Questionnaire->getId())));
            } else {
                log_info('アンケート登録チェックエラー', array($id));
                $app->addError('admin.register.failed', 'admin');
            }
        }

        // 検索結果の保持
        $builder = $app['form.factory']
            ->createBuilder('admin_search_questionnaire');
        $searchForm = $builder->getForm();
        if ('POST' === $request->getMethod()) {
            $searchForm->handleRequest($request);
        }

        return $app->render('Questionnaire/edit.twig', array(
            'Questionnaire' => $Questionnaire,
            'form' => $form->createView(),
            'searchForm' => $searchForm->createView(),
            'id' => $id,
        ));
    }

    public function delete(Application $app, Request $request, $id = null)
    {
        $this->isTokenValid($app);
        $session = $request->getSession();
        $page_no = intval($session->get('eccube.admin.questionnaire.search.page_no'));
        $page_no = $page_no ? $page_no : Constant::ENABLED;

        if (!is_null($id)) {
            $Questionnaire = $app['eccube.repository.questionnaire']->find($id);
            if (!$Questionnaire) {
                $app->deleteMessage();

                return $app->redirect($app->url('admin_questionnaire_page', array('page_no' => $page_no)).'?resume='.Constant::ENABLED);
            }

            if ($Questionnaire instanceof \Eccube\Entity\Questionnaire) {
                log_info('アンケート削除開始', array($id));

                $Questionnaire->setDelFlg(Constant::ENABLED);

                $app['orm.em']->persist($Questionnaire);
                $app['orm.em']->flush();

                log_info('アンケート削除完了', array($id));

                $app->addSuccess('admin.delete.complete', 'admin');
            } else {
                log_info('アンケート削除エラー', array($id));
                $app->addError('admin.delete.failed', 'admin');
            }
        } else {
            log_info('アンケート削除エラー', array($id));
            $app->addError('admin.delete.failed', 'admin');
        }

        return $app->redirect($app->url('admin_questionnaire_page', array('page_no' => $page_no)).'?resume='.Constant::ENABLED);
    }

    public function copy(Application $app, Request $request, $id = null)
    {
        $this->isTokenValid($app);
        if (!is_null($id)) {
            $Questionnaire = $app['eccube.repository.questionnaire']->find($id);
            if ($Questionnaire instanceof \Eccube\Entity\Questionnaire) {
                $CopyQuestionnaire = clone $Questionnaire;
                $Disp = $app['eccube.repository.master.disp']->find(\Eccube\Entity\Master\Disp::DISPLAY_HIDE);
                $CopyQuestionnaire->setStatus($Disp);

                // 子要素を複製
                $QuestionnaireDetails = $CopyQuestionnaire->getQuestionnaireDetails();
                foreach ($QuestionnaireDetails as $QuestionnaireDetail) {
                    $CopyQuestionnaireDetail = clone $QuestionnaireDetail;
                    $CopyQuestionnaireDetail->setQuestionnaire($CopyQuestionnaire);
                    $app['orm.em']->persist($CopyQuestionnaireDetail);
                    $app['orm.em']->persist($CopyQuestionnaire);
                    foreach ($QuestionnaireDetail['QuestionnaireDetailChoices'] as $QuestionnaireDetailChoice) {
                        $CopyQuestionnaireDetailChoice = clone $QuestionnaireDetailChoice;
                        $CopyQuestionnaireDetailChoice->setQuestionnaireDetail($CopyQuestionnaireDetail);
                        $app['orm.em']->persist($CopyQuestionnaireDetailChoice);
                        $app['orm.em']->persist($CopyQuestionnaireDetail);
                    }
                }

                // 添付ファイルを複製
                $Attachments = $CopyQuestionnaire->getQuestionnaireAttachments();
                foreach ($Attachments as $Attachment) {

                    // 添付ファイルを新規作成
                    $extension = pathinfo($Attachment->getFileName(), PATHINFO_EXTENSION);
                    $filename = date('mdHis').uniqid('_').'.'.$extension;
                    try {
                        $fs = new Filesystem();
                        $fs->copy($app['config']['questionnaire_attachment_save_realdir'].'/'.$Attachment->getFileName(), $app['config']['questionnaire_attachment_save_realdir'].'/'.$filename);
                    } catch (\Exception $e) {
                        // エラーが発生しても無視する
                    }
                    $Attachment->setFileName($filename);

                    $app['orm.em']->persist($Attachment);
                }

                $app['orm.em']->persist($CopyQuestionnaire);
                $app['orm.em']->flush();

                $app->addSuccess('アンケートを複製しました。', 'admin');

                return $app->redirect($app->url('admin_questionnaire_edit', array('id' => $CopyQuestionnaire->getId())));
            } else {
                $app->addError('アンケートの複製に失敗しました。', 'admin');
            }
        } else {
            $app->addError('アンケートの複製に失敗しました。', 'admin');
        }
        return $app->redirect($app->url('admin_questionnaire'));
    }

    public function display(Application $app, Request $request, $id = null)
    {
        if (!is_null($id)) {
            return $app->redirect($app->url('product_detail', array('id' => $id, 'admin' => '1')));
        }

        return $app->redirect($app->url('admin_questionnaire'));
    }
}
