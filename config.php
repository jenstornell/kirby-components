<?php
include __DIR__ . '/lib/helpers.php';
include __DIR__ . '/lib/core.php';
#include __DIR__ . '/lib/tests.php';

$Core = new \JensTornell\Components\Core();

$out = $Core->run();
$out['routes'] = [
  [
    'pattern' => 'components/assets/(:all)/(:any).(css|gif|js|jpg|png|svg|webp)',
    'action'  => function($id, $filename, $extension) {
      $site = site();
      $Core = new \JensTornell\Components\Core();
      $Helpers = new \JensTornell\Components\Helpers();
      $roots = $Helpers->rootsToArray($Core->roots);

      foreach($roots as $root) {
        $filepath = "$root/$id/$filename.$extension";
        if(!file_exists($filepath)) continue;

        return new Response(file_get_contents($filepath), $extension);
      }

      return false;
    }
  ]
];

Kirby::plugin('jenstornell/components', $out);