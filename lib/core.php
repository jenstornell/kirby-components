<?php
namespace JensTornell\Components;

class Core {
  // The root(s) of the component folder(s)
  public $roots;

  // Class instances
  private $Helpers;

  // Allowed files in template and snippet components
  private $whitelist = [
    'template' => [
      'blueprint.yml',
      'component.php',
      'controller.php',
      'autoload.php'
    ],
    'snippet' => [
      'component.php',
      'autoload.php'
    ]
  ];

  // Setup construct stuff
  public function __construct() {
    $this->Helpers = new \JensTornell\Components\Helpers();
    $this->roots = option('jenstornell.components.roots', kirby()->roots()->site() . '/components');
  }

  // Run task
  function run() {
    return $this->toRing($this->Helpers->rootsToArray($this->roots));
  }

  // Generate array for the plugin ring
  function toRing($roots) {

    foreach($roots as $root) {
      $iterator = $this->Helpers->toIterator($root);

      foreach($iterator as $path) {
        if($path->isDir()) continue;

        // Path as string
        $path = strval($path);

        // Filename with extension
        $filename = basename($path);

        // Raw name
        $raw_id = $this->Helpers->rawId($root, $path);

        // Stem - Filename without extension
        $stem = pathinfo($filename)['filename'];

        // Type - Template or snippet
        $type = $this->Helpers->type($raw_id);

        // Name without --
        $id = $this->Helpers->id($raw_id, $type);
        if(empty($id)) continue;

        // Allowed - Is filename allowed
        if(!in_array($filename, $this->whitelist[$type])) continue;

        // Registry - If component, return template or snippet, else the stem
        if($stem == 'component')
          $registry = $type;
        else
          $registry = $stem;

        // Load autoloaded files
        if($registry == 'autoload') {
          require_once $path;
          continue;
        }

        // Generate array for the plugin ring
        if($registry == 'controller') {
          $controller = require_once $path;
          $data[$registry . 's'][$id] = $controller;
        } else {
          $data[$registry . 's'][$id] = $path;
        }
      }
    }
    $data['routes'] = $this->assetsRoute();
    return $data;
  }

  // Route for css, gif, js, jpg, png, svg, webp
  function assetsRoute() {
    return [
      [
        'pattern' => 'components/assets/(:all)/(:any).(css|gif|js|jpg|png|svg|webp)',
        'action'  => function($id, $filename, $extension) {
          $site = site();
          $roots = $this->Helpers->rootsToArray($this->roots);
    
          foreach($roots as $root) {
            $filepath = "$root/$id/$filename.$extension";
            if(!file_exists($filepath)) continue;
    
            return new Response(file_get_contents($filepath), $extension);
          }
    
          return false;
        }
      ]
    ];
  }
}