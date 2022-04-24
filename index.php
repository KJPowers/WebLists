<?php
require __DIR__ . '/vendor/autoload.php';

$opts = ['extension' => 'html'];

$m = new Mustache_Engine(array(
  'entity_flags'     => ENT_QUOTES,
  'loader'           => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views', $opts),
  'partials_loader'  => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views/partials', $opts),
  'pragmas'          => [Mustache_Engine::PRAGMA_ANCHORED_DOT],
  'strict-callables' => 'true',
));
//echo $m->render('index', array('planet' => 'World!')); // "Hello World!"
/*echo $m->render('test', [
  'title' => 'my links',
  'links' => [
    ['href' => 'https://mustache.github.io'],
    ['href' => 'https://github.com/mustache/spec', 'title' => 'The Mustache spec'],
    ['href' => 'https://github.com/bobthecow/mustache.php', 'title' => 'Mustache.php'],
] ]);
*/

$vars = [
  'lists' => array(
    ['class' => 'nav-link', 'name' => '<First List>'],
    ['class' => 'nav-link', 'name' => '<Second List>'],
    ['class' => 'nav-link', 'name' => '<Third List>'],
    ['class' => 'nav-link', 'name' => '<Fourth List>'],
  ),
 'items' => array(
    ['class' => 'nav-link', 'name' => 'Item one'],
    ['class' => 'nav-link', 'name' => 'Item two'],
    ['class' => 'nav-link', 'name' => 'Item three'],
  ),
];

echo $m->render('index', $vars);
