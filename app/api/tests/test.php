<?php

require_once dirname(__FILE__).'/../mongo/crud.php';

class MongoTests extends PHPUnit_Framework_TestCase {

	define('MONGO_HOST', '127.0.0.1');
	define('DB', 'noteApp');

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
     * @expectedException MongoException
     */
	public function testCatchMongoConnectionException(){
	        $conn = new MongoClient("mongodb://localhost:27018"); // bad port
		    $this->db = $conn->{'noteApp'};
		    $this->collection = $this->db->{'notes'};
	}

	public function testCanCreate(){
		// Arrange
        $doc = json_decode("{
                'title': 'Sample Title G'
              , 'description': 'Sample description for this note.'
              , 'userid': '0001'
              , 'text':'This is a sample note text.'
              , 'type':'text'
            }");
            
		// Act
        $resp = mongoCreate("noteApp","notes", $doc);

		// Assert
        $this->assertNotNull($resp);

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