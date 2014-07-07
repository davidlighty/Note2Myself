<?php

require_once dirname(__FILE__).'/../mongo/crud.php';

class mongoTests extends \PHPUnit_Framework_TestCase {

	private $db;
	private $collection;

	// What to do before each test
	protected function setUp() {
		try {
		    $conn = new MongoClient(); // connects to localhost:27017
		    $this->db = $conn->{$db};
		    $this->collection = $this->db->{'notes'};
		}catch(Exception $err){
			var_dump($err);
		}
	}

	// What to do after each test
  	protected function tearDown() {}

  	///////////// TESTS ////////////////

	public function testCanConnect(){
		  $this->assertNull($this->db);  // Failing test
	}

	public function testCanCreate(){

	}

	public function testCanRead(){

	}

	public function testCanUpdate(){

	}

	public function testCanDelete(){

	}

	///////////// TESTS ////////////////
  	
		
}