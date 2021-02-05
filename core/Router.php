<?php

class Router
{
    protected $routes;

    public function __construct($definitions)
    {
        $this->routes = $this->compileRoutes($definitions);
    }

    // ルーティング定義配列を内部用に変換する
    //ルーティング定義配列をコンストラクタのパラメータとして受け取り、変換したものを$routesプロパティとして設定。
    //URLを「/」で区切って、動的パラメータがあるか判定して、加工後再度「/」でつなげて$routesに格納。

    //array( '/item/:action' => array('controller' => 'item'))
    public function compileRoutes($definitions)
    {
        $routes = array();

        foreach ($definitions as $url => $params) {
            //explode string の内容を delimiter で分割した文字列の配列を返します。
            //$url = /item/:action
            $tokens = explode('/', ltrim($url, '/'));
            /*Array
            (
                [0] => item
                [1] => :action
            )
            */
            foreach ($tokens as $i => $token) {
                //$tokenのなかに:があったら正規表現の形式にして変換する
                if (0 === strpos($token, ':')) {
                    //$name = action
                    $name = substr($token, 1);
                    $token = '(?P<' . $name . '>[^/]+)';
                }
                /*
                Array
                    (
                        [0] => item
                        [1] => (?P<action>[^/]+)
                    )
                */
                $tokens[$i] = $token;
            }
            //分割したURLをサイドスラッシュでつなげて変換済みの値として$routes変数に格納
            ///item/(?P<action>[^/]+)
            $pattern = '/' . implode('/', $tokens);
            $routes[$pattern] = $params;
        }
        
        /*
        Array
            (
                [/item/(?P<action>[^/]+)] => Array
                    (
                        [controller] => item
                    )

            )
        */
        return $routes;
    }

    //指定されたPATH_INFOを元にルーティングパラメータを特定する
    //ルーティングのマッチングをする関数。
    //compileRoutes()で変換したルーティング定義配列を利用して、マッチングを行う。
    //マッチした場合は、コントローラー・アクション・ルーティングパラメータを合体させて「params」として変数を返しています。
    public function resolve($path_info)
    {
        //print_r($path_info);
        ///account/signup
        if ('/' !== substr($path_info, 0, 1)) {
            $path_info = '/' . $path_info;
        }

        //print_r($this->routes);
        //Array ( [/account] => Array ( [controller] => account [action] => index ) [/account/(?P[^/]+)] => Array ( [controller] => account ) )
        foreach ($this->routes as $pattern => $params) {
            //print_r($pattern);
            //1loop /account 2loop /account/(?P[^/]+)
            //print_r($params);
            //1Array ( [controller] => account [action] => index ) 2Array ( [controller] => account )
            if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
                //var_dump($matches);
                //$matches array(3) { [0]=> string(15) "/account/signup" ["action"]=> string(6) "signup" [1]=> string(6) "signup" }

                //print_r($params);
                //Array ( [controller] => account )
                $params = array_merge($params, $matches);
                //print_r($params);
                return $params;
                //$paramsのなかみ
                //Array ( [controller] => account [0] => /account/signup [action] => signup [1] => signup )
            }
        }

        return false;
    }
}

///user/:user_name	(status	user)    /user/(?P<user_name>[^/]+) KEYをuser_nameにして取り出せる
///user/:user_name/status/:id	(status	show)    /user/(?P<user_name>[^/]+)/status/(?P<id>[^/]+) KEYをuser_name 、idにしてその中身が取り出せる
///account/:action  /account/(?P<action>[^/]+) KEY action　同上
