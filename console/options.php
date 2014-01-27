<?php
require_once 'define.php';

use Application_Model_Option as Option;

$option = Option::create();
$option->getContent()->setName('Префикс телефона');
$option->setOptionKey('phone-prefix');
$option->getContent()->setValue('(8452)');
$option->save();

$option = Option::create();
$option->getContent()->setName('Телефон без префикса');
$option->setOptionKey('phone-without-prefix');
$option->getContent()->setValue('47-77-88');
$option->save();

$option = Option::create();
$option->getContent()->setName('Телефон с префиксом');
$option->setOptionKey('phone-with-prefix');
$option->getContent()->setValue('(8452) 47-77-88');
$option->save();

$option = Option::create();
$option->getContent()->setName('Email');
$option->setOptionKey('email');
$option->getContent()->setValue('medoptima-s@yandex.ru');
$option->save();

$option = Option::create();
$option->getContent()->setName('Адрес');
$option->setOptionKey('address');
$option->getContent()->setValue('улица Тархова 39, Саратов, Россия, 162539');
$option->save();