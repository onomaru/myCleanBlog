<?php

//Controller.
//Controllerクラスはアプリケーションごとに子クラスを作成しその中にアクションを定義するようにするため
//Controllerクラス自身は抽象クラスとして定義する
abstract class Controller
{
    protected $controller_name;
    protected $action_name;
    protected $application;
    protected $request;
    protected $response;
    protected $session;
    protected $db_manager;
    protected $auth_actions = array();

    //コンストラクタ
    //コンストラクタ自身にアプリケーションクラスを渡す
    public function __construct($application)
    {
        //get_class — オブジェクトのクラス名を返す
        //$controller_name は　UserControllerならuser(小文字)
        //後ろの10文字ぶん取り除くということ
        $this->controller_name = strtolower(substr(get_class($this), 0, -10));

        $this->application = $application;
        $this->request     = $application->getRequest();
        $this->response    = $application->getResponse();
        $this->session     = $application->getSession();
        $this->db_manager  = $application->getDbManager();
    }

    /**
     * アクションを実行
     *
     * @param string $action
     * @param array $params
     * @return string レスポンスとして返すコンテンツ
     *
     * @throws UnauthorizedActionException 認証が必須なアクションに認証前にアクセスした場合
     */
    public function run($action, $params = array())
    {
        $this->action_name = $action;

        $action_method = $action . 'Action';
        if (!method_exists($this, $action_method)) {
            $this->forward404();
        }

        if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
            throw new UnauthorizedActionException();
        }

        //可変関数
        $content = $this->$action_method($params);

        return $content;
    }

    /**
     * ビューファイルのレンダリング
     *
     * @param array $variables テンプレートに渡す変数の連想配列
     * @param string $template ビューファイル名(nullの場合はアクション名を使う)
     * @param string $layout レイアウトファイル名
     * @return string レンダリングしたビューファイルの内容
     */
    protected function render($variables = array(), $template = null, $layout = 'layout')
    {
        $defaults = array(
            'request'  => $this->request,
            'base_url' => $this->request->getBaseUrl(),
            'session'  => $this->session,
        );

        $view = new View($this->application->getViewDir(), $defaults);

        if (is_null($template)) {
            $template = $this->action_name;
        }

        $path = $this->controller_name . '/' .$template;
        //print_r($template);
        //signup
        //print_r($path);
        //account/signup
        //print_r($variables);
        //Array ( [user_name] => [password] => [_token] => aeb1914445d89bf3bc16634d6338d430ef512226 )
        //print_r($layout);
        //
        
        //文字列としてのビューファイル
        return $view->render($path, $variables, $layout);
    }

    //404エラー画面を出力
    protected function forward404()
    {
        throw new HttpNotFoundException('Forwarded 404 page from '
            . $this->controller_name . '/' . $this->action_name);
    }

    //指定されたURLへリダイレクト
    protected function redirect($url)
    {
        if (!preg_match('#https?://#', $url)) {
            $protocol = $this->request->isSsl() ? 'https://' : 'http://';
            $host = $this->request->getHost();
            $base_url = $this->request->getBaseUrl();

            $url = $protocol . $host . $base_url . $url;
        }

        $this->response->setStatusCode(302, 'Found');
        $this->response->setHttpHeader('Location', $url);
    }

    //CSRFトークンを生成しセッションに格納した上でトークンを返す
    //$form_name account/signupなど
    protected function generateCsrfToken($form_name)
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = $this->session->get($key, array());
        if (count($tokens) >= 10) {
            //配列の先頭の要素を消す(古いものから消す)
            array_shift($tokens);
        }

        $token = sha1($form_name . session_id() . microtime());
        $tokens[] = $token;

        $this->session->set($key, $tokens);

        return $token;
    }

    //CSRFトークンが妥当かチェック
    //送られてきたトークンとセッションに格納されたトークンを比較した結果を返し
    //同時にセッションからトークンを削除するメソッド
    protected function checkCsrfToken($form_name, $token)
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = $this->session->get($key, array());

        //配列から指定された値を検索する
        if (false !== ($pos = array_search($token, $tokens, true))) {
            unset($tokens[$pos]);
            $this->session->set($key, $tokens);

            return true;
        }

        return false;
    }

    //指定されたアクションが認証済みでないとアクセスできないか判定
    protected function needsAuthentication($action)
    {
        if ($this->auth_actions === true
            //is_array 与えられた変数が配列かどうかを検査
            //in_array 配列に与えられた値があるかチェックする
            || (is_array($this->auth_actions) && in_array($action, $this->auth_actions))
        ) {
            return true;
        }

        return false;
    }
}
