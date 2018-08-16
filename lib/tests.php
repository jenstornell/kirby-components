<?php
$Helpers = new \JensTornell\Components\Helpers();

$root_path1 = 'D:\projects\plugins\kirby-components/site/components';
$root_path2 = ['D:\projects\plugins\kirby-components/site/components'];

$path_template = "D:\projects\plugins\kirby-components/site/components\--test2\component.php";
$path_snippet = 'D:\projects\plugins\kirby-components/site/components\test\sub\component.php';
$path_template_snippet = "D:\projects\plugins\kirby-components/site/components\--test2\sub\component.php";

// rootsToArray
$roots = $Helpers->rootsToArray($root_path1);
$roots2 = $Helpers->rootsToArray($root_path2);

$iterator = $Helpers->toIterator($root_path1);

// rawId
$rawId_template = $Helpers->rawId($root_path1, $path_template);
$rawId_template_snippet = $Helpers->rawId($root_path1, $path_template_snippet);
$rawId_snippet = $Helpers->rawId($root_path1, $path_snippet);

// Type
$type_template = $Helpers->type($rawId_template);
$type_template_snippet = $Helpers->type($rawId_template_snippet);
$type_snippet = $Helpers->type($rawId_snippet);

// id
$id_template = $Helpers->id($rawId_template, $type_template);
$id_template_snippet = $Helpers->id($rawId_template_snippet, $type_template_snippet);
$id_snippet = $Helpers->id($rawId_snippet, $type_snippet);

## Tests

// toIterator
if(empty(iterator_count($iterator))) throw new Exception('Invalid iterator or empty iterator');

// rootsToArray
if($roots[0] != $root_path1) throw new Exception('Invalid root path 1');
if($roots2[0] != $root_path1) throw new Exception('Invalid root path 2');

// rawId
if($rawId_template != '--test2') throw new Exception('Invalid rawId on template');
if($rawId_template_snippet != '--test2/sub') throw new Exception('Invalid rawId on template snippet');
if($rawId_snippet != 'test/sub') throw new Exception('Invalid rawId on snippet snippet');

// Type
if($type_template != 'template') throw new Exception('Invalid type on template');
if($type_template_snippet != 'snippet') throw new Exception('Invalid type on template snippet');
if($type_snippet != 'snippet') throw new Exception('Invalid type on snippet');

// ID
if($id_template != 'test2') throw new Exception('Invalid type on template');
if($id_template_snippet != '--test2/sub') throw new Exception('Invalid type on template snippet');
if($id_snippet != 'test/sub') throw new Exception('Invalid type on snippet');

echo 'Success!';
die;