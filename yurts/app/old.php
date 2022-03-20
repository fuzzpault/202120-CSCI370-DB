<html>
<head>
</head>

<body>
<h1>Hello world again againnn</h1>
qIfmp9GU0poK
<form method="GET" >
	<label>Hotel Number</label>
		<input name="hotelNo" type="text" /><br>
	<label>Hotel Name</label>
		<input name="hotelName" type="text" /><br>
	<label>Hotel City</label>
		<input name="hotelCity" type="text" /><br>
	<input type="submit" value="Submit this stuff"/>
</form>

<?php 
  $dsn = 'mysql:host=localhost;dbname=hotels';
  $username = 'root';
  $password = '';
  $pdo = new PDO($dsn, $username, $password);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Insert data if needed
  if(isset($_GET['hotelNo'])){
  	$sql = 'INSERT INTO hotel '.
  		   '(hotelNo, hotelName, city) ' .
  		   'VALUES (?, ?, ?);';
  	$statement = $pdo->prepare($sql);
  	$statement->bindValue(1, $_GET['hotelNo']);
  	$statement->bindValue(2, $_GET['hotelName']);
  	$statement->bindValue(3, $_GET['hotelCity']);
  	try{
  		$ret = $statement->execute();
  	}catch(Exception $e){
  		echo "Insert error: ", $e->getMessage();
  	}
  }


  // Print table data
  $sql = 'SELECT * from hotel;';
  $statement = $pdo->query($sql);
  $count = 0;

  while($row = $statement->fetch(PDO::FETCH_ASSOC)){
  	echo '<p>';
  	foreach(array_keys($row) as $k){
  		echo "$k: $row[$k] <br>\n";
  	}
  	echo '</p>';
  	$count++;
  }
  echo "There are $count rows.\n";
?>
</body>
</html>