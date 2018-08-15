<?php
include __DIR__ . '/lib/helpers.php';
include __DIR__ . '/lib/core.php';
include __DIR__ . '/lib/tests.php';

$Core = new \JensTornell\Components\Core();

Kirby::plugin('jenstornell/components', $Core->run());