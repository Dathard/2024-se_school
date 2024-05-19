<?php
require __DIR__ . '/app/bootstrap.php';

$objectManager = new \Core\ObjectManager();
$objectManager->get(\Core\WebapiRouter::class)->execute();