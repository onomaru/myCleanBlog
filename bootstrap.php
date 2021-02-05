<?php

require 'core/ClassLoader.php';

$loader = new ClassLoader();
//modelsとcoreのディレクトリを対象ディレクトリにする
$loader->registerDir(dirname(__FILE__).'/core');
$loader->registerDir(dirname(__FILE__).'/models');
$loader->register();

//core, models内の読み込まれてないクラスが、new や extends された時、
//オートロードスタックに登録された loadClass を実行する
