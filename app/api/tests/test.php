<?php

require_once dirname(__FILE__).'/../mongo/crud.php';

class MongoTests extends PHPUnit_Framework_TestCase {

	private $db;
	private $collection;

	// What to do before each test
	protected function setUp() {
		try {
		    $conn = new MongoClient(); // connects to localhost:27017
		    $this->db = $conn->{'noteApp'};
		    $this->collection = $this->db->{'notes'};
		}catch(Exception $err){
			var_dump($err);
		}
	}

	// What to do after each test
  	protected function tearDown() {}

  	///////////// TESTS ////////////////

	public function testCanConnect(){
		  $this->assertNotNull($this->db);
	}
	
	/**
     * @expectedException MongoConnectionException
     */
	public function testCatchMongoConnectionException(){
	    mongoCreate($server, $db, $collection, $document);
	}

	public function testCanCreate(){
		// Arrange

		// Act

		// Assert

	}

	public function testCanRead(){
		// Arrange

		// Act

		// Assert
	}

	public function testCanUpdate(){
		// Arrange

		// Act

		// Assert
	}

	public function testCanDelete(){
		// Arrange

		// Act

		// Assert
	}

	///////////// TESTS ////////////////
  	
		
}