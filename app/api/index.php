<?php

  require 'Slim/Slim/Slim.php';
  require 'mongo/mongoLayer.php';

  /**
  * Create API App
  */
  $app = new Slim();

  /**
   * Routing
   */
  $app->get(    '/api/:collection',      '_list');
  $app->post(   '/api/:collection',      '_create');
  $app->get(    '/api/:collection/:id',  '_read');
  $app->put(    '/api/:collection/:id',  '_update');
  $app->delete( '/api/:collection/:id',  '_delete');

  /**
  * List
  *
  * Get a list of documents from the mongo db
  */
  function _list($db, $collection){
    
    $select = array(
      'limit' =>    (isset($_GET['limit']))   ? $_GET['limit'] : false, 
      'page' =>     (isset($_GET['page']))    ? $_GET['page'] : false,
      'filter' =>   (isset($_GET['filter']))  ? $_GET['filter'] : false,
      'regex' =>    (isset($_GET['regex']))   ? $_GET['regex'] : false,
      'sort' =>     (isset($_GET['sort']))    ? $_GET['sort'] : false
    );
    
    $data = MongoLayer::list( 
      $collection,
      $select
    );
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
  }

  // Create
  function _create($db, $collection){

    $document = json_decode(Slim::getInstance()->request()->getBody(), true);

    $data = mongoCreate(
      MONGO_HOST, 
      $db, 
      $collection, 
      $document
    ); 
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
  }

  // Read
  function _read($db, $collection, $id){

    $data = mongoRead(
      MONGO_HOST,
      $db,
      $collection,
      $id
    );
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
  }

  // Update 
  function _update($db, $collection, $id){

    $document = json_decode(Slim::getInstance()->request()->getBody(), true);

    $data = mongoUpdate(
      MONGO_HOST, 
      $db, 
      $collection, 
      $id,
      $document
    ); 
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
  }

  // Delete
  function _delete($db, $collection, $id){

    $data = mongoDelete(
      MONGO_HOST, 
      $db, 
      $collection, 
      $id
    ); 
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
  }

  /**
  * Start Api app
  */
  $app->run();