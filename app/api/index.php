<?php
/*

    Notes API using Slim Framework
    David Lighty 2014
    Comp 2052

    http://docs.slimframework.com/

*/
session_start();
require 'Slim/Slim.php';
use Slim\Slim;
Slim::registerAutoloader();

$app = new Slim(array(
	'debug' => true,
    'mode' => 'development'
));

$app->get('/',function(){
	echo "API Start Page";
});

$app->get('/notes', function(){
	//'getNotes'
	echo getNotes();
});

// $app->get('/notes/:id', authorize('user'),	'getNote');
// $app->get('/notes/search/:query', authorize('user'), 'getNotesByName');
// $app->get('/notes/modifiedsince/:timestamp', authorize('user'), 'findByModifiedDate');

// // I add the login route as a post, since we will be posting the login form info
// $app->post('/login', 'login');

$app->run();
// api/index.php




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