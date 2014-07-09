<?php

/**
 *   Main API Access Layer
 *
 *   David Lighty - A00843511
 *   BCIT | Comp 2920
 *
 *   Goal:  Create an API based access layer for multiple front ends using Slim
 *   framework, MongoDB.
 *   Main API Paths:
 *     - User authentication
 *     - Notes CRUD
 */

require 'Slim/Slim.php';
require 'mongo/mongoLayer.php';

/**
 * Create API App
 */
use Slim\Slim;
Slim::registerAutoloader();
$app = new Slim();

/**
 * Routing
 *
 * Dynamic routing that will map the :collection (notes/users, etc)
 * Those routes that require authentication add the 'authenticate' as a middleware option
 *   ==>  (route,middleware,function)
 */
$app->get('/api/:collection', 'authenticate', '_list');
$app->post('/api/:collection', 'authenticate', '_create');
$app->get('/api/:collection/:id', 'authenticate', '_read');
$app->put('/api/:collection/:id', 'authenticate', '_update');
$app->delete('/api/:collection/:id', 'authenticate', '_delete');

/**
 * _list
 *
 * Get a list of documents from the mongo db
 */

function _list($db, $collection) {
	$select = array(
		'limit'  => (isset($_GET['limit']))?$_GET['limit']:false,
		'page'   => (isset($_GET['page']))?$_GET['page']:false,
		'filter' => (isset($_GET['filter']))?$_GET['filter']:false,
		'regex'  => (isset($_GET['regex']))?$_GET['regex']:false,
		'sort'   => (isset($_GET['sort']))?$_GET['sort']:false
	);
	$data = MongoLayer::list(
		$collection,
		$select
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * _create
 *
 * Insert a new record into the db
 */
function _create($collection) {
	$document = json_decode(Slim::getInstance()->request()->getBody(), true);
	$data     = MongoLayer::create(
		$collection,
		$document
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * _read
 *
 * Find a record in the db
 */
function _read($collection, $id) {
	$data = MongoLayer::read(
		$collection,
		$id
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * _update
 *
 * Update a record in the db
 */
function _update($collection, $id) {
	$document = json_decode(Slim::getInstance()->request()->getBody(), true);
	$data     = MongoLayer::update(
		$collection,
		$id,
		$document
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * _delete
 *
 * Remove a record in the db
 */
function _delete($collection, $id) {
	$data = MongoLayer::delete(
		$collection,
		$id
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * Authentication Requirement
 *
 * Routes that require user auth token
 */

/**
 * authenticate
 *
 * Detect our auth token or fail with 401
 */
function authenticate(\Slim\Route $route) {
	$app = \Slim\Slim::getInstance();
	if (validateUserKey() === false) {
		$app->halt(401);
	}
}

/**
 * validateUserKey
 *
 * Main validation function
 */
function validateUserKey($uid, $key) {
	// insert your (hopefully complex) validation routine here
	return true;// Allow for now all
}

/**
 * Start Api app
 */
$app->run();