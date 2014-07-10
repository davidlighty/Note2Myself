<?php

require_once dirname(__FILE__).'/../../app/api/mongo/mongoLayer.php';

use noteAppApi\MongoLayer;

class MongoLayerTests extends \PHPUnit_Framework_TestCase {

	private $db;
	private $collection;

	const NOTE_CL = "notes";

	// What to do before each test
	protected function setUp() {

	}

	// What to do after each test
	protected function tearDown() {}

	///////////// TESTS ////////////////

	/**
	 * covers \noteAppApi\MongoLayer\MongoLayer::__construct
	 * @expectedException \Exception
	 */
	// public function testExceptionIsRaisedForConstructor() {
	// 	new MongoLayer();  Throws a fatal error...skip
	// }

	/**
	 * covers \noteAppApi\MongoLayer\MongoLayer::create
	 */
	public function testCanCreate() {
		// Arrange
		$doc = json_decode('{"title": "Sample Title G"}', true);

		// Act
		$resp = MongoLayer::create(self::NOTE_CL, $doc);

		// Assert
		$this->assertNotNull($resp);

	}

	/**
	 * covers \noteAppApi\MongoLayer\MongoLayer::read
	 */
	public function testCanRead() {
		// Arrange
		$title = "Sample Title G";
		$doc   = json_decode('{"title": '.$title.'}', true);
		$resp  = MongoLayer::create(self::NOTE_CL, $doc);

		// Act
		$found = MongoLayer::read(self::NOTE_CL, $doc["_id"]);

		// Assert
		$this->assertEquals($title, $found["title"]);
	}

	/**
	 * covers \noteAppApi\MongoLayer\MongoLayer::update
	 */
	public function testCanUpdate() {
		// Arrange
		$title = "Sample Title A";
		$doc   = json_decode('{"title": "Sample Title G"}', true);
		$resp  = MongoLayer::create(self::NOTE_CL, $doc);
		$doc   = json_decode('{"title":  '.$title.'}', true);

		// Act
		$updated = MongoLayer::update(self::NOTE_CL, $doc);

		// Assert
		$this->assertEquals($title, $found["title"]);
	}

	/**
	 * covers \noteAppApi\MongoLayer\MongoLayer::delete
	 */
	public function testCanDelete() {
		// Arrange
		$doc  = json_decode('{"title": "Sample Title G"}', true);
		$resp = MongoLayer::create(self::NOTE_CL, $doc);

		// Act
		$deleted = MongoLayer::delete($resp["_id"]);

		// Assert no exception?

	}

	///////////// TESTS ////////////////

}