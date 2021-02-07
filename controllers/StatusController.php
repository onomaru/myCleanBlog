<?php

class StatusController extends Controller
{
    //index
    protected $auth_actions = array('post','postView'.'edit','update','delete','user');

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
        $title = $this->request->getPost('title');


        $errors = array();

        if (!strlen($body)) {
            $errors[] = '内容を入力してください';
        } elseif (mb_strlen($body) > 1255) {
            $errors[] = '内容は1255 文字以内で入力してください';
        }

        if (count($errors) === 0) {
            $user = $this->session->get('user');
            $this->db_manager->get('Status')->insert($user['id'], $title, $body);
            //var_dump($title);
            return $this->redirect('/');
        }



        return $this->render(array(
            'errors'   => $errors,
            'body'     => $body,
            'title'    => $title,
            '_token'   => $this->generateCsrfToken('status/post'),
        ), 'postView');
    }

    //ユーザの投稿一覧
    public function userAction($params)
    {
        //controllerクラスから
        //$content = $this->$action_method($params);

        //ユーザが存在しているかチェック
        //$param ルーティングパラメータからユーザIDを取得する
        //$paramsの中身はルーティングからpreg_matchなどで一致したaction,controller,:user_nameなどが入ってる
        // '/user/:user_name'
        //      => array('controller' => 'status', 'action' => 'user'),
        //get('User')ことでUserRepositoryクラスのインスタンスを取ってきてそこからfetchByUserNameが使える
        $user = $this->db_manager->get('User')
            ->fetchByUserName($params['user_name']);
        if (!$user) {
            $this->forward404();
        }

        $statuses = $this->db_manager->get('Status')
            ->fetchAllByUserId($user['id']);
        

        return $this->render(array(
            'user'      => $user,
            'statuses'  => $statuses,
        ));
    }

    //投稿編集
    public function editAction($params)
    {
        $status = $this->db_manager->get('Status')
            ->fetchByIdAndUserName($params['id'], $params['user_name']);

        if (!$status) {
            $this->forward404();
        }

        return $this->render(array(
            'status' => $status,
            '_token'    => $this->generateCsrfToken('status/edit'),
        ));
    }

    //投稿アップデート（画面なし）
    public function updateAction($params)
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('status/edit', $token)) {
            return $this->redirect('/');
        }

        $body = $this->request->getPost('body');
        $title = $this->request->getPost('title');


        $errors = array();

        if (!strlen($body)) {
            $errors[] = '内容を入力してください';
        } elseif (mb_strlen($body) > 1255) {
            $errors[] = '内容は1255 文字以内で入力してください';
        }

        if (count($errors) === 0) {
            $this->db_manager->get('Status')->update($params['id'], $title, $body);

            //var_dump($params);
            return $this->redirect('/');
        }

        //errorの時はもう一度投稿内容を保持するために$statues(投稿内容)をとってくる必要がある

        $status = $this->db_manager->get('Status')
        ->fetchByIdAndUserName($params['id'], $params['user_name']);
        //var_dump($status);
        return $this->render(array(
            'errors'   => $errors,
            'body'     => $body,
            'title'    => $title,
            'status' => $status,
            '_token'   => $this->generateCsrfToken('status/edit'),
        ), 'edit');
    }

    //投稿削除（画面なし）
    public function deleteAction($params)
    {
        $result = $this->db_manager->get('Status')
            ->delete($params['id']);

        if ($result) {
            $this->forward404();
        }

        return $this->redirect('/');
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
