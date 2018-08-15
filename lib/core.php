<?php
namespace JensTornell\Components;

class Core {
  // The root(s) of the component folder(s)
  private $roots;

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
    $iterator = $this->Helpers->toIterator($roots);

    foreach($iterator as $path) {
      if($path->isDir()) continue;

      // Path as string
      $path = strval($path);

      // Filename with extension
      $filename = basename($path);

      // Raw name
      $raw_id = $this->Helpers->rawId($roots, $path);

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
      $data[$registry . 's'][$id] = $path;
    }

    return $data;
  }
}