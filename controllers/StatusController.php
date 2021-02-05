<?php

class StatusController extends Controller
{
    //index
    protected $auth_actions = array('post','postView');

    //ホームページにあたるindexアクション
    //ログインしているユーザのホームページにあたる
    //自身の投稿とフォローしているユーザの投稿をまとめて表示
    public function indexAction()
    {
        $user = $this->session->get('user');
        //$statues 取得した投稿
        $statuses = $this->db_manager->get('Status')
            ->fetchAllPersonalArchivesByUserId($user['id']);

        return $this->render(array(
            'statuses' => $statuses,
            'body'     => '',
            '_token'   => $this->generateCsrfToken('status/post'),
        ));
    }

    //投稿画面を出力
    public function postViewAction()
    {
        $user = $this->session->get('user');
        //$statues 取得した投稿
        $statuses = $this->db_manager->get('Status')
            ->fetchAllPersonalArchivesByUserId($user['id']);

        return $this->render(array(
            '_token'   => $this->generateCsrfToken('status/post'),
        ));
    }

    //投稿処理を行う
    public function postAction()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('status/post', $token)) {
            return $this->redirect('/');
        }

        $body = $this->request->getPost('body');

        $errors = array();

        if (!strlen($body)) {
            $errors[] = 'ひとことを入力してください';
        } elseif (mb_strlen($body) > 200) {
            $errors[] = 'ひとことは200 文字以内で入力してください';
        }

        if (count($errors) === 0) {
            $user = $this->session->get('user');
            $this->db_manager->get('Status')->insert($user['id'], $body);

            return $this->redirect('/');
        }

        //errorの時は再度index画面に戻るが、index画面は投稿一覧も表示しているため
        //もう一度$statues(投稿内容)をとってくる必要がある
        $user = $this->session->get('user');
        $statuses = $this->db_manager->get('Status')
            ->fetchAllPersonalArchivesByUserId($user['id']);

        return $this->render(array(
            'errors'   => $errors,
            'body'     => $body,
            'statuses' => $statuses,
            '_token'   => $this->generateCsrfToken('status/post'),
        ), 'postView');
    }

    //投稿詳細
    public function showAction($params)
    {
        $status = $this->db_manager->get('Status')
            ->fetchByIdAndUserName($params['id'], $params['user_name']);

        if (!$status) {
            $this->forward404();
        }

        return $this->render(array('status' => $status));
    }
}
