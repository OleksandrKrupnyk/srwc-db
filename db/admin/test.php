<?php

use zukr\author\Author;
use zukr\base\Base;
use zukr\univer\UniverRepository;

require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';
Base::init();
//$a = new Author();
//$a->load(['Author' => ['name' => 'Ivan','place'=>'D']]);
//var_dump(Author::find());
//var_dump( (new UniverRepository())->getDropList());
var_dump( (new UniverRepository())->getInvitedDropList());
?>