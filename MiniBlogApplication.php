<?php

class MiniBlogApplication extends Application
{
    protected $login_action = array('account', 'signin');

    //ルートディレクトリへの場所を返す
    public function getRootDir()
    {
        return dirname(__FILE__);
    }

    //ルーティング定義配列を返す
    protected function registerRoutes()
    {
        //:id などは$paramsから取ってこれる
        return array(
            '/contact'
            => array('controller' => 'account', 'action' => 'contact'),
            '/about'
            => array('controller' => 'account', 'action' => 'about'),
            '/account'
            => array('controller' => 'account', 'action' => 'index'),
            '/account/:action'
            => array('controller' => 'account'),
            '/'
            => array('controller' => 'status', 'action' => 'index'),
            '/status/postView'
            => array('controller' => 'status', 'action' => 'postView'),
            '/status/post'
            => array('controller' => 'status', 'action' => 'post'),
            '/status/edit/:user_name/:id'
            => array('controller' => 'status', 'action' => 'edit'),
            '/status/update/:id'
            => array('controller' => 'status', 'action' => 'update'),
            '/status/delete/:id'
            => array('controller' => 'status', 'action' => 'delete'),
            '/user/:user_name'
            => array('controller' => 'status', 'action' => 'user'),
            '/user/:user_name/status/:id'
            => array('controller' => 'status', 'action' => 'show'),
        );
    }

    //アプリケーションの設定を行う ここにDBへの接続情報を記述
    protected function configure()
    {
        $this->db_manager->connect('master', array(
            'dsn'      => 'mysql:dbname=cleanBlog;host=localhost;charset=utf8',
            'user'     => 'root',
            'password' => 'root',
        ));
    }
}
