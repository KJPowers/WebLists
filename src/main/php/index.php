<?php
ini_set('display_errors', 1);

// Dependencies
require __DIR__ . '/vendor/autoload.php';
require_once 'config.php';
require_once 'models/Models.php';
require_once 'controller/Controller.php';

// Mustache engine
$opts = ['extension' => 'html'];
$m = new Mustache_Engine(array(
  'entity_flags'     => ENT_QUOTES,
  'loader'           => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views', $opts),
  'partials_loader'  => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views/partials', $opts),
  'pragmas'          => [Mustache_Engine::PRAGMA_ANCHORED_DOT],
  'strict-callables' => 'true',
));

// Page parameters
$uuid = isset($_GET['list']) ? $_GET['list'] : null;

// Controller
//Controller::handleFormData();
//$ctrl = new Controller();
$mdl = Controller::loadModel($uuid);

// Render
echo $m->render('index', $mdl);

// debug
//echo var_dump($mdl);
