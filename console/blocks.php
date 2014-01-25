<?php
require_once 'define.php';

use Application_Model_TextBlock as Block;

$data = array(
    array(
        'name' => 'Клиника',
        'alias' => 'about-clinic',
        'text' => 'В нашей клинике 183 м2 это как две трёхкомнатных квартиры в спальном районе. У каждого врача свой кабинет. Открылись мы в 2011 году, поэтому оборудование новое и современное (Chirana-dental, Kodak-trophy, NSK, VDW, Ems, W&H, Mocom, Euronda).'
    ),
    array(
        'name' => 'Вакансии',
        'alias' => 'opened-vacancies',
        'text' => 'Врач терапевт.
Напишите нам job@optima.ru'
    ),
    array(
        'name' => 'Как проехать',
        'alias' => 'how-to-get',
        'text' => 'Остановки «6-ой микрорайон»:
— 8-я маршрутка;
— 74-й автобус;
и «ул. Топольчанская» — 53-й автобус.'
    )
);

foreach ($data as $values) {
    $block = Block::create();
    foreach ($values as $name => $value) {
        $block->{'set' . ucfirst($name)}($value);
    }
    $block->save();
}