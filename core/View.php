<?php
 //アウトプットバッファリングという機能を利用するとechoやrequireで出力が発生するタイミングを制御することができる
 //アウトプットバッファリングとは、出力をバッファに溜めておき、出力したいタイミングで出力させる機能である

 //ビューファイルの読み込み ビューファイルに渡す変数の制御
 class View
 {
     //ビューファイルを格納しているViewディレクトリへの絶対パスを指定
     protected $base_dir;
     //ビューファイルに変数を渡すときデフォルトで渡す変数を設定
     protected $defaults;
     //レイアウトファイルの読み込みを行う際に渡す変数 例えばページタイトルなど
     protected $layout_variables = array();
 
     //コンストラクタ
     public function __construct($base_dir, $defaults = array())
     {
         $this->base_dir = $base_dir;
         $this->defaults = $defaults;
     }
 
     //レイアウトに渡す変数を指定
     public function setLayoutVar($name, $value)
     {
         $this->layout_variables[$name] = $value;
     }
 
     //ビューファイルを読み込むメソッド（内容を整形して表示）
     //ビューファイル内でrendarメソッドを呼びだすことで別のビューファイルを読みこむことも出来る
     //$_path ビューファイルへのパスを指定
     //$_variables ビューファイルに渡す変数を指定
     //  $this->render('account/inputs', array(
     //     'user_name' => $user_name, 'password' => $password,
     // ));
     //$_layout レイアウトファイル名を指定 falseの場合レイアウトの読み込みは行わない
     public function render($_path, $_variables = array(), $_layout = false)
     {
         //var_dump($_layout);
         $_file = $this->base_dir . '/' . $_path . '.php';
         //print_r($_file);
         ///Applications/MAMP/htdocs/php_miniBlog/views/account/signup.php

         //連想配列を指定し連想配列のキーを変数名に、連想配列の値を変数の値として展開する関数
         //この状態でビューファイルを読み込むと、ビューファイルから展開した変数にアクセス可能
         extract(array_merge($this->defaults, $_variables));
 
         //アウトプットバッファリング開始
         ob_start();
         //フラッシュ機能をオフに設定。バッファの上限が来ると自動で出力されてしまうので、無効化する
         ob_implicit_flush(0);
 
         //ビューファイル読み込み
         //ビューファイルを文字列として取得
         require $_file;
         //var_dump($_file);
         // ex　/Applications/MAMP/htdocs/php_miniBlog/views/account/signup.php
         // /Applications/MAMP/htdocs/php_miniBlog/views/layout.php"
 
         //バッファ内容の取得とバッファのクリア
         $content = ob_get_clean();
         //var_dump($content);
         if ($_layout) {
             //var_dump($_layout);
             //layout
             $content = $this->render(
                 $_layout,
                 array_merge(
                     $this->layout_variables,
                     array(
                     '_content' => $content,
                 )
                 )
             );
         }
         

         //文字列としてのビューファイルをリターン
         return $content;
     }
 
     //指定された値をHTMLエスケープする
     public function escape($string)
     {
         return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
     }
 }
