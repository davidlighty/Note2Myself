<?php

namespace noteAppApi;

use \MongoClient;
use \MongoConnectionException;
use \MongoException;
use \MongoId;
use \MongoRegex;

/**
 * MongoDB CRUD functions
 *
 * Flattening _id objects for better JS models in the front end
 *
 */

class MongoLayer {

	// DB Constants
	const DB_NAME = 'notesApp';

	// List Sort Constants
	const MONGO_LIST_DEFAULT_PAGE_SIZE = 500;
	const MONGO_LIST_MAX_PAGE_SIZE     = false;// set to a number to enforce a max page size

	private function __construct() {}

	/**
	 * MongoDB CRUD functions
	 *
	 * Flattening _id objects for better JS models in the front end
	 *
	 */

	/**
	 * Create (insert)
	 */
	public static function create($collection, $document) {

		try {

			$conn       = new MongoClient();
			$_db        = $conn->{self::DB_NAME};
			$collection = $_db->{ $collection};
			$collection->insert($document);
			$conn->close();

			$document['_id'] = $document['_id']->{'$id'};

			return $document;

		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: '.$e->getMessage());
		}

	}

	/**
	 * Read (findOne)
	 */
	public static function read($collection, $id) {

		try {

			$conn       = new MongoClient();
			$_db        = $conn->{self::DB_NAME};
			$collection = $_db->{ $collection};

			$criteria = array(
				'_id' => new MongoId($id)
			);

			$document = $collection->findOne($criteria);
			$conn->close();

			$document['_id'] = $document['_id']->{'$id'};

			return $document;

		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: '.$e->getMessage());
		}

	}

	/**
	 * Update (set properties)
	 */
	public static function update($collection, $id, $document) {

		try {

			$conn       = new MongoClient();
			$_db        = $conn->{self::DB_NAME};
			$collection = $_db->{ $collection};

			$criteria = array(
				'_id' => new MongoId($id)
			);

			// make sure that an _id never gets through
			unset($document['_id']);

			$collection->update($criteria, array('$set' => $document));
			$conn->close();

			$document['_id'] = $id;

			return $document;

		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: '.$e->getMessage());
		}

	}

	/**
	 * Delete (remove)
	 */
	public static function delete($collection, $id) {

		try {

			$conn       = new MongoClient();
			$_db        = $conn->{self::DB_NAME};
			$collection = $_db->{ $collection};

			$criteria = array(
				'_id' => new MongoId($id)
			);

			$collection->remove(
				$criteria,
				array(
					'safe' => true
				)
			);

			$conn->close();

			return array('success' => 'deleted');

		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: '.$e->getMessage());
		}

	}

	/**
	 * Find single instance
	 */
	public static function findOne($collection, $criteria) {
		try {
			$conn       = new MongoClient();
			$_db        = $conn->{self::DB_NAME};
			$collection = $_db->{ $collection};

			$document = $collection->findOne(
				$criteria
			);

			$conn->close();

			return $document;

		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: '.$e->getMessage());
		}
	}

	/**
	 * Find all instances
	 */
	public static function find($collection, $criteria) {
		try {
			$conn       = new MongoClient();
			$_db        = $conn->{self::DB_NAME};
			$collection = $_db->{ $collection};

			$document = $collection->find(
				$criteria
			);

			$conn->close();

			return $document;

		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: '.$e->getMessage());
		}
	}

	/**
	 * Validate User
	 */
	public static function validateUser($collection, $user) {
		try {
			$conn       = new MongoClient();
			$_db        = $conn->{self::DB_NAME};
			$collection = $_db->{ $collection};

			$criteria = array(
				'email'    => $user['email'],
				'password' => $user['password']
			);

			$document = $collection->findOne(
				$criteria
			);

			$conn->close();

			return $document;

		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: '.$e->getMessage());
		}
	}

	/**
	 * Collection count
	 */
	public static function collectionCount($collection, $query = null) {

		try {

			$conn       = new MongoClient();
			$_db        = $conn->{self::DB_NAME};
			$collection = $_db->{ $collection};

			if ($query) {
				return $collection->count($query);
			} else {
				return $collection->count();
			}

		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: '.$e->getMessage());
		}

	}

	/**
	 * Mongo list with sorting and filtering
	 *
	 *  $select = array(
	 *    'limit' => 0,
	 *    'page' => 0,
	 *    'filter' => array(
	 *      'field_name' => 'exact match'
	 *    ),
	 *    'regex' => array(
	 *      'field_name' => '/expression/i'
	 *    ),
	 *    'sort' => array(
	 *      'field_name' => -1
	 *    )
	 *  );
	 *
	 * @uid if blank (admin role), get all?
	 */
	public static function getList($collection, $select = null) {

		try {

			$conn       = new MongoClient();
			$_db        = $conn->{self::DB_NAME};
			$collection = $_db->{ $collection};

			$criteria = NULL;

			if (isset($select['filter']) && count($select['filter'])) {
				$criteria = $select['filter'];
			}

			// add regex match filters if they exist

			if (isset($select['wildcard']) && count($select['wildcard'])) {
				foreach ($select['wildcard'] as $key => $value) {
					$criteria[$key] = new MongoRegex($value);
				}
			}

			// lastly check and add uid filter
			if (isset($select['userid'])) {
				$criteria['userid'] = $select['userid'];
			}

			// get results

			if ($criteria) {
				$cursor = $collection->find($criteria);
			} else {
				$cursor = $collection->find();
			}

			// sort the results if specified

			if (isset($select['sort']) && $select['sort'] && count($select['sort'])) {
				$sort = array();
				print_r($select);
				foreach ($select['sort'] as $key => $value) {
					$sort[$key] = (int) $value;
				}
				$cursor->sort($sort);
			}

			// set a limit

			if (isset($select['limit']) && $select['limit']) {
				if (self::MONGO_LIST_MAX_PAGE_SIZE && $select['limit'] > self::MONGO_LIST_MAX_PAGE_SIZE) {
					$limit = self::MONGO_LIST_MAX_PAGE_SIZE;
				} else {
					$limit = $select['limit'];
				}
			} else {
				$limit = self::MONGO_LIST_DEFAULT_PAGE_SIZE;
			}

			if ($limit) {
				$cursor->limit($limit);
			}

			// choose a page if specified

			if (isset($select['page']) && $select['page']) {
				$skip = (int) ($limit*($select['page']-1));
				$cursor->skip($skip);
			}

			// prepare results to be returned

			$output = array(
				'total'    => $cursor->count(),
				'pages'    => ceil($cursor->count()/$limit),
				'criteria' => $criteria,
				'results'  => array(),
			);

			foreach ($cursor as $result) {
				// 'flattening' _id object in line with CRUD functions
				$result['_id']       = $result['_id']->{'$id'};
				$output['results'][] = $result;
			}

			$conn->close();

			return $output;

		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: '.$e->getMessage());
		}

	}

}