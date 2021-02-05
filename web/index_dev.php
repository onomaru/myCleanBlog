<?php
 //フロントコントローラ　開発用
 //http://localhost:8888/php_miniBlog/web/index_dev.php/account/signup
 require '../bootstrap.php';
 require '../MiniBlogApplication.php';

 //デバックモードをtrueに設定
$app = new MiniBlogApplication(true);
$app->run();
