<?php

use zukr\author\Author;
use zukr\base\Base;

require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';
Base::init();
$a = new Author();
$a->load(['Author' => ['name' => 'Ivan']]);
var_dump($a->findById(['1',41]));
?>