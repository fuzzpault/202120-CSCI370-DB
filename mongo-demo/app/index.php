<?php

//phpinfo();
// connect to mongodb

$bulk = new MongoDB\Driver\BulkWrite;

$doc = [

'clientName' => 'Raizeleee',

'email' => 'raizel@whatever.com',

'tag' => 'Admin User',

];

$bulk->insert($doc);

$manager = new MongoDB\Driver\Manager('mongodb://host.docker.internal:27017');
//var_dump($manager);

$result = $manager->executeBulkWrite('test.books', $bulk);

# setting your options and filter
$filter  = [];
$options = ['sort'=>array('_id'=>-1),'limit'=>30]; # limit -1 from newest to oldest

#constructing the querry
$query = new MongoDB\Driver\Query($filter, $options);

#executing
$cursor = $manager->executeQuery('test.books', $query);

echo "dumping results<br>";
foreach ($cursor as $document) {
    var_dump($document);
}
/*

$m = new MongoClient();
   echo "Connection to database successfully";
    
   // select a database
   $db = $m->mydb;
   echo "Database mydb selected";
   $collection = $db->mycol;
   echo "Collection selected succsessfully";
    
   $document = array( 
      "title" => "MongoDB", 
      "description" => "database", 
      "likes" => 100,
      "url" => "http://www.tutorialspoint.com/mongodb/",
      "by" => "tutorials point"
   );
    
   $collection->insert($document);
   echo "Document inserted successfully";


*/
?>