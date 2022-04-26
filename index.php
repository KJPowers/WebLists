<?php
require __DIR__ . '/vendor/autoload.php';
require_once 'models/Models.php';

$opts = ['extension' => 'html'];

$m = new Mustache_Engine(array(
  'entity_flags'     => ENT_QUOTES,
  'loader'           => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views', $opts),
  'partials_loader'  => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views/partials', $opts),
  'pragmas'          => [Mustache_Engine::PRAGMA_ANCHORED_DOT],
  'strict-callables' => 'true',
));

$mdl = new Index();
$mdl->lists = array(
    new NavbarWebList('abc', '<List 1>', 'The first list'),
    new NavbarWebList('def', '<List 2>', 'The second list'),
    new NavbarWebList('ghi', '<List 3>', 'The third list'),
  );
$mdl->items = array(
    new NavbarItem(1, '<Item 1>', 'foo'),
    new NavbarItem(2, '<Item 2>', ''),
    new NavbarItem(3, '<Item 3>', ''),
  );

echo $m->render('index', $mdl);

// debug
//echo var_dump($mdl);
