<?php
namespace JensTornell\Components;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Helpers {

  // ID - Name without --
  function id($name, $type) {
    if($type == 'template' && strpos($name, '--') === 0)
        return substr($name, 2);
        
    return $name;
  }

  // Type - Template or snippet
  function type($name) {
    $is_template = strpos($name, '--') === 0;
    $is_level_0 = strpos($name, '/') === false;

    if($is_template && $is_level_0)
        return 'template';
        
    return 'snippet';
  }

  // ID including --
  function rawId($root, $path) {
    $root = realpath($root);
    $parts = pathinfo($path);
    $name = strtr($parts['dirname'], [
        $root => '',
    ]);
    
    if($name == $parts['dirname']) return;

    $name = strtr($name, [
        "\\" => '/',
    ]);

    $name = trim($name, '/');

    return $name;
  }

  // Loop root and return iterator
  function toIterator($root) {
    $root = realpath($root);
    $iterator = new \AppendIterator();

    if(!file_exists($root))
      die('The components folder could not be found');

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
    //$directoryIterator->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);

    return $iterator;
  }

  // Make root(s) an array no matter what
  function rootsToArray($option_roots) {
    if(is_string($option_roots))
      $roots[] = $option_roots;
    else
      $roots = $option_roots;

    return $roots;
  }
}