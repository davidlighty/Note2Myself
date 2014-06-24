<?php
/*

    Notes API using Slim Framework
    David Lighty 2014
    Comp 2052

    http://docs.slimframework.com/

*/
session_start();

require 'Slim/Slim.php';
require 'mongo/crud.php';
require 'mongo/list.php';
require 'mongo/command.php';

use Slim\Slim;
Slim::registerAutoloader();

$app = new Slim(array(
	'debug' => true,
    'mode' => 'development'
));

// Set up DB Mongo connection
define('MONGO_HOST', '127.0.0.1');
define('DB', 'noteApp');


$app->get('/',function(){
	echo "API Start Page";
});

$app->get('/notes','_list');

// $app->get('/notes/:id', authorize('user'),	'getNote');
// $app->get('/notes/search/:query', authorize('user'), 'getNotesByName');
// $app->get('/notes/modifiedsince/:timestamp', authorize('user'), 'findByModifiedDate');

// // I add the login route as a post, since we will be posting the login form info
// $app->post('/login', 'login');

$app->run();
// api/index.php

// Return a collection 
function _list(){
  
  $select = array(
    'limit' =>    (isset($_GET['limit']))   ? $_GET['limit'] : false, 
    'page' =>     (isset($_GET['page']))    ? $_GET['page'] : false,
    'filter' =>   (isset($_GET['filter']))  ? $_GET['filter'] : false,
    'regex' =>    (isset($_GET['regex']))   ? $_GET['regex'] : false,
    'sort' =>     (isset($_GET['sort']))    ? $_GET['sort'] : false
  );
  
  $data = mongoList(
    MONGO_HOST, 
    DB, 
    'notes',
    $select
  );
  echo json_encode($data);

}


function getNotes(){
	$sampleNotes="[{
		'title': 'Sample Title A'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title B'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title C'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title D'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title E'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title F'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title G'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title H'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title I'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
}]";

return $sampleNotes;
}