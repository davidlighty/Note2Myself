<?php
session_start();

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

use noteAppApi\MongoLayer;
use Slim\Slim;

/**
 * Create API App
 */
Slim::registerAutoloader();
$app = new Slim();

/**
 * Routing
 *
 * Dynamic routing that will map the :collection (notes/users, etc)
 * Those routes that require authentication add the 'authenticate' as a middleware option
 *   ==>  (route,middleware,function)
 */

// Login Route
$app->post('/login', 'login');

// Notes Routes
$app->get('/:collection', authorize('user'), '_list');
$app->post('/:collection', authorize('user'), '_create');
$app->get('/:collection/:id', authorize('user'), '_read');
$app->put('/:collection/:id', authorize('user'), '_update');
$app->delete('/:collection/:id', authorize('user'), '_delete');

/**
 * _list
 *
 * Get a list of documents from the mongo db
 */

function _list($collection) {
	$select = array(
		'limit'  => (isset($_GET['limit']))?$_GET['limit']:false,
		'page'   => (isset($_GET['page']))?$_GET['page']:false,
		'filter' => (isset($_GET['filter']))?$_GET['filter']:false,
		'regex'  => (isset($_GET['regex']))?$_GET['regex']:false,
		'sort'   => (isset($_GET['sort']))?$_GET['sort']:false
	);
	$data = MongoLayer::getList(
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
 * Quick and dirty login function with hard coded credentials (admin/admin)
 * This is just an example. Do not use this in a production environment
 */
function login() {
	if (!empty($_POST['email']) && !empty($_POST['password'])) {
		// normally you would load credentials from a database.
		// This is just an example and is certainly not secure
		if ($_POST['email'] == 'admin' && $_POST['password'] == 'admin') {
			$user             = array("email" => "admin", "firstName" => "Clint", "lastName" => "Berry", "role" => "user");
			$_SESSION['user'] = $user;
			echo json_encode($user);
		} else {
			echo '{"error":{"text":"You shall not pass..."}}';
		}
	} else {
		echo '{"error":{"text":"Username and Password are required."}}';
	}
}

/**
 * authorize
 *
 * Detect our auth token or fail with 401
 */
function authorize($role = "user") {
	return function () use ($role) {
		// Get the Slim framework object
		$app = Slim::getInstance();
		// First, check to see if the user is logged in at all
		if (!empty($_SESSION['user'])) {
			// Next, validate the role to make sure they can access the route
			// We will assume admin role can access everything
			if ($_SESSION['user']['role'] == $role ||
				$_SESSION['user']['role'] == 'admin') {
				//User is logged in and has the correct permissions... Nice!
				return true;
			} else {
				// If a user is logged in, but doesn't have permissions, return 403
				$app->halt(403, 'You shall not pass!');
			}
		} else {
			// If a user is not logged in at all, return a 401
			$app->halt(401, 'You shall not pass!');
		}
	};
}

/**
 * validateUserKey
 *
 * Main validation function
 */
function validateUserKey($uid, $key) {
	// insert your (hopefully complex) validation routine here
	return false;// Allow for none
}

/**
 * Start Api app
 */
$app->run();