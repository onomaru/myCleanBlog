<?php
 
  
 //http://localhost:8888/php_miniBlog/web/index.php/account/signup
 //http://localhost:8888/php_miniBlog/web/account/signup 同じ
 
 require '../bootstrap.php';
 require '../MiniBlogApplication.php';

$app = new MiniBlogApplication(false);
$app->run();
