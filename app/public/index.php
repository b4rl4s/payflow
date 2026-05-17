<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

$pdo = $GLOBALS['payflow.pdo'];
$config = $GLOBALS['payflow.config'];

$app = new Payflow\Http\Application($pdo, $config);
$app->run();
