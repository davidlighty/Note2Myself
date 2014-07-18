<?php
session_start();
$aip   = $_SERVER['REMOTE_ADDR'];
$bip   = $_SERVER['HTTP_X_FORWARDED_FOR'];
$agent = $_SERVER['HTTP_USER_AGENT'];

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
require 'lib/mongoLayer.php';

use noteAppApi\MongoLayer;
use Slim\Slim;

/**
 * Constants for the app
 */
define('MIN_PHP', '5.5');
define('MODE', 'debug');
define('IMGLIMIT', 4);

/**
 * Create API App
 */
Slim::registerAutoloader();
$app = new Slim();

$app->config(array(
		'templates.path' => './templates'
	));

// Login Route
$app->post('/login', 'login');
$app->post('/forgot', 'forgotpw');
$app->get('/unlock', 'unlockAccount');
$app->post('/logout', 'logout');

// Bing photo
$app->get('/bingphoto', 'bingphoto');

// Images
$app->post('/imageupload', authorize('user'), 'uploadImage');
$app->get('/image/:id', authorize('user'), 'getImage');

/**
 * Routing
 *
 * Dynamic routing that will map the :collection (notes/users, etc)
 * Those routes that require authentication add the 'authenticate' as a middleware option
 *   ==>  (route,middleware,function)
 */

// Notes Routes
$app->get('/:collection', authorize('user'), '_list');
$app->post('/:collection', authorize('user'), '_create');
$app->get('/:collection/:id', authorize('user'), '_read');
$app->put('/:collection/:id', authorize('user'), '_update');
$app->delete('/:collection/:id', authorize('user'), '_delete');

/**
 * Route Targets
 */

/**
 * _list
 *
 * Get a list of documents from the mongo db
 */

function _list($collection) {
	$uid    = $_SESSION['user']['_id'];
	$select = array(
		'userid' => $uid,
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
	$document           = json_decode(Slim::getInstance()->request()->getBody(), true);
	$document['userid'] = $_SESSION['user']['_id'];
	$data               = MongoLayer::create(
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
		$uid,
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
 * uploadImage
 *
 * Uploading Image
 */
function uploadImage() {
	$allowed = array('jpg', 'jpeg', 'gif', 'svg', 'png');
	$ctlName = 'newnoteimage';
	$uid     = $_SESSION['user']['_id'];
	// http://cmlenz.github.io/jquery-iframe-transport/
	echo '<textarea data-type="application/json">';
	$imgCount = MongoLayer::getCount('notes', array('type' => 'image', 'userid' => $uid));
	if ($imgCount < IMGLIMIT) {
		if (isset($_FILES[$ctlName]) && $_FILES[$ctlName]['error'] == 0) {
			$extension = pathinfo($_FILES[$ctlName]['name'], PATHINFO_EXTENSION);
			if (!in_array(strtolower($extension), $allowed)) {
				echo '{"error":{"text":"Unsupported filetype.", "filetype":"'.$extension.'"}}';
			} else {
				// Save into db
				$metadata = array("userid" => $uid, "filetype" => $extension);
				$data     = MongoLayer::saveImage($ctlName, $metadata);
				echo '{"success": {"id":"'.$data.'","filename":"'.$_FILES[$ctlName]['name'].'"}}';
			}
		}
	} else {
		echo '{"error":{"text":"Image limit reached.", "count":"'.$imgCount.'"}}';
	}
	echo '</textarea>';
}

/**
 * getImage
 *
 * Create Image from db
 * $id = image id attribute
 */
function getImage($id) {
	// retrieve file from collection
	$file = MongoLayer::getImage($id);
	if (!$file) {
		header("Content-Type: application/json");
		echo '{"error":{"text":"Image not found"}}';
		exit;
	}
	// send headers and file data
	//echo var_dump($file);
	header('Content-type: image/'.substr($file->filename, -3));
	echo $file->getBytes();
}

/**
 * Authentication Requirement
 *
 * Routes that require user auth token
 */

/**
 *	forgotpw
 *
 *  Validate email and send email
 */
function forgotpw() {
	$document = json_decode(Slim::getInstance()->request()->getBody(), true);
	if (!empty($document['email']) && !is_null(MongoLayer::findOne('users', array('email' => $document['email'])))) {
		// Valid user...
		$user             = MongoLayer::findOne('users', array('email' => $user['email']));
		$newPass          = generatePW();
		$user['password'] = password_hash($newPass, PASSWORD_DEFAULT);
		// The message
		// message
		$message = '
					<html>
					<head>
					  <title>NoteApp Password Recovery</title>
					</head>
					<body>
						<p>There was a request for password recovery</p>
					 	<p>Here is your password: '.$newPass.'</p>
					</body>
					</html>
					';

		sendEmail($user['email'], 'Password Recovery Email', $message);

		// mail sent, now update db...otherwise keep old pw
		$data = MongoLayer::update(
			'users',
			$user['_id'],
			$user
		);

		header("Content-Type: application/json");
		echo '{"success":{"text":"Email sent."}}';
	} else {
		header("Content-Type: application/json");
		echo '{"error":{"code":"500","text":"Incorrect email or user does not exist."}}';
	}
}

/**
 * lockAccount
 *
 * Lock the specified account, change password, and email an unlock link
 */
function lockAccount($user) {
	$newPass          = generatePW();
	$user['password'] = password_hash($newPass, PASSWORD_DEFAULT);
	$unlockKey        = generatePW();
	$user['lockkey']  = password_hash($unlockKey, PASSWORD_DEFAULT);
	$URL              = getURL().'/api/unlock';// http://localhost/note2myself/api/unlock is desired test url.
	$unlockLink       = '<a href='.$URL.'?key='.$unlockKey.'&email='.$user['email'].'>Unlock Account</a>';

	// account locked, update before attempting to send email.
	$data = MongoLayer::update(
		'users',
		$user['_id'],
		$user
	);

	// message
	$message = '
		<html>
		<head>
		  <title>Account Unlock</title>
		</head>
		<body>
			<p>This account is locked due to too many failed login attmepts.</p>
			<p>Your password has been changed:'.$newPass.'</p>
		 	<p>'.$unlockLink.'</p>
		</body>
		</html>
	';

	sendEmail($user['email'], 'Unlock Your Account', $message);

	header("Content-Type: application/json");
	echo '{"success":{"text":"Email sent."}}';
}

/**
 * unlockAccount
 *
 * Unlock this account based upon email and unlock key.
 */
function unlockAccount() {
	// Get the Slim framework object
	$app = Slim::getInstance();

	$email     = $app->request()->params('email');
	$unlockKey = $app->request()->params('key');
	if (is_null($email) || is_null($unlockKey)) {
		// Fail.
		$app->halt(403, 'Bad Unlock Paramaters.');
		exit;
	} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// Not an email address
		$app->halt(403, 'Invalid email provided.');
		exit;
	}

	$user = MongoLayer::findOne('users', array('email' => $email));
	if (is_null($isUser)) {
		// Not a valid user
		$app->halt(403, 'Invalid user.');
		exit;
	} else if (is_null($isUser['lockkey'])) {
		// Not locked.
		$app->redirect('../index.html');
		exit;// take me to the homepage.
	} else if (!password_verify($unlockKey, $user['lockkey'])) {
		// Invalid Key
		$app->halt(403, 'Invalid unlock reference.');
		exit;
	}

	// Pass, unlock the user.
	$user['lockkey'] = null;
	// account locked, update before attempting to send email.
	$data = MongoLayer::update(
		'users',
		$user['_id'],
		$user
	);

	$app->redirect('../index.html');
	exit;// take me to the homepage.
}

/**
 * generatePW
 *
 * Generate a random password
 */
function generatePW() {
	return base_convert(rand(78364164096, 2821109907455), 10, 36);
}

/**
 * login
 *
 * Quick and dirty login function
 */
function login() {
	global $aip, $bip, $agent;
	header("Content-Type: application/json");
	$document = json_decode(Slim::getInstance()->request()->getBody(), true);
	if (!empty($document['email']) && !empty($document['password'])) {
		if ($document['register']) {
			register($document);
			exit;
		} else {
			$user = MongoLayer::findUserByEmail('users', $document);
			if (!is_null($user)) {
				if ($user['locked']) {
					// Currently Locked out.
					lockAccount();
					echo '{"error":{"code":"200","text":"User does not exist."}}';
				} else if (password_verify($document['password'], $user['password'])) {
					// Success
					$_SESSION['user']     = $user;
					$_SESSION['attempts'] = 0;
					$_SESSION['ident']    = hash("sha256", $aip.$bip.$agent);
					$_SESSION['access']   = time();
					// Debug only
					//$user['ident']=$_SESSION['ident'];
					//$user['data']='{"$ident":"'.$ident.'",$aip":"'. $aip .'","$bip":"'.$bip.'","$agent":"'.$agent.'"}';
					unset($user['password']);
					echo json_encode($user);
				} else {
					// Bad Password
					$_SESSION['attempts'] += 1;
					if ($_SESSION['attempts'] >= 3) {
						// Lock out.
						lockAccount($user);
						echo '{"error":{"code":"225","text":"Account Locked."}}';
					} else {
						echo '{"error":{"code":"220","text":"Incorrect password.","attempts":"'.$_SESSION['attempts'].'"}}';
					}

				}
			} else {
				echo '{"error":{"code":"200","text":"User does not exist."}}';
			}
		}
	} else {
		echo '{"error":{"code":"230","text":"Username and Password are required."}}';
	}
}

/**
 * logout
 *
 * Reset our session.
 */
function logout() {
	$_SESSION = array();
	session_destroy();
	header("Content-Type: application/json");
	echo '{"success":{"text":"Session Removed."}}';
}

/**
 * register
 *
 * Register a new user.
 */
function register($user) {
	$isUser = MongoLayer::findOne('users', array('email' => $user['email']));
	if (is_null($isUser)) {
		// Hash password using php 5.5 functions (http://php.net/manual/en/function.password-hash.php)
		$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
		$newUser          = array(
			'email'    => $user['email'],
			'password' => $user['password'],
			'role'     => 'user'
		);
		$data = MongoLayer::create(
			'users',
			$newUser
		);
		$_SESSION['user'] = $data;
		unset($data['password']);
		header("Content-Type: application/json");
		echo json_encode($data);
		exit;
	} else {
		header("Content-Type: application/json");
		echo '{"error":{"text":"Email address already in use."}}';
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
			if (!authorizeSession()) {
				$app->halt(401, 'Session Auth Failed');
				return false;
			}
			// Next, validate the role to make sure they can access the route
			// We will assume admin role can access everything
			if ($_SESSION['user']['role'] == $role ||
				$_SESSION['user']['role'] == 'admin') {
				//User is logged in and has the correct permissions... Nice!
				$_SESSION['access'] = time();
				return true;
			} else {
				// If a user is logged in, but doesn't have permissions, return 403
				$app->halt(403, 'Not sufficent security.');
			}
		} else {
			// If a user is not logged in at all, return a 401
			$app->halt(401, 'Session not found.');
		}
	};
}

/**
 * authorizeSession
 *
 * Attempt to prevent session hijack issues
 */
function authorizeSession() {
	global $aip, $bip, $agent;
	// Get the Slim framework object
	$app             = Slim::getInstance();
	$lastAccessInMin = (time()-$_SESSION['access'])/60;
	if ($lastAccessInMin >= 20) {
		// Too long. Expired
		logout();
		$app->halt(401, 'Session Expired. '.$lastAccessInMin.' '.$_SESSION['access']);
		return false;
	}
	// Do this every time the client makes a request to the server, after authenticating
	$ident = hash("sha256", $aip.$bip.$agent);
	if ($ident != $_SESSION['ident']) {
		logout();
		//echo '{"error":{"$ident":"'.$ident.'",$aip":"'. $aip .'","$bip":"'.$bip.'","$agent":"'.$agent.'"}';
		return false;
	}
	return true;
}

/**
 * sendEmail
 *
 * Sends an email to the user
 */
function sendEmail($to, $subject, $msgHtml) {
	// Send
	$headers = 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";

	$headers .= 'To:'.$to."\r\n";
	$headers .= 'From: NoteApp <support@noteapp.com>'."\r\n";

	try {
		// Mail it
		mail($to, $subject, $msgHtml, $headers);
	} catch (Exception $err) {

	}

}

/**
 * Misc Helper Methods
 */

/**
 * getURL
 *
 * Need this for when we are using a subdomain website. =>  http://localhost/testwebsite/
 * Thanks : http://stackoverflow.com/questions/2820723/how-to-get-base-url-with-php
 */
function getURL() {
	return sprintf(
		"%s://%s%s",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'?'https':'http',
		$_SERVER['SERVER_NAME'],
		$_SERVER['REQUEST_URI']
	);
}

/**
 * Get the Bing photo of the day
 *
 * Reads the RSS feed in JSON and passes this down. To be used as "wallpaper" for the website.
 */
function bingphoto() {
	$bingjson = file_get_contents('http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1');
	header("Content-Type: application/json");
	echo $bingjson;
}

/**
 * verifyPHPFailIfNessecary
 *
 * Verify the minimum allowable PHP version
 */
function verifyPHPFailIfNessecary() {
	$ver = phpversion();
	if (version_compare(phpversion(), MIN_PHP, '<')) {
		die('Requires PHP 5.5');
	}

	return true;
}

/**
 * Start Api app
 *
 * Verify our PHP version before we spin up.
 */
if (verifyPHPFailIfNessecary()) {
	$app->run();
}
