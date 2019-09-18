<?php

use zukr\author\Author;
use zukr\base\Base;
use zukr\base\html\Html;
use zukr\univer\UniverRepository;

require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';
Base::init();
//$a = new Author();
//$a->load(['Author' => ['name' => 'Ivan','place'=>'D']]);
//var_dump(Author::find());
//var_dump( (new UniverRepository())->getDropList());
//var_dump( (new UniverRepository())->getInvitedDropList());
echo Html::select('univer', 5,(new UniverRepository())->getInvitedDropList(),[
    'prompt'=>'Help me',
    'id'=>'univer-home',
    'size'=>5
]);
?>