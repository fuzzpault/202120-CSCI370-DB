<?php   

echo "<h1>just sql</h1>";

function doQuery($mem, $pdo, $sql){
	if($pdo === FALSE){
			$dsn = 'mysql:host=localhost;dbname=yurt_reservations';
		      $username = 'root';
		      $password = '';
		      $pdo = new PDO($dsn, $username, $password);
		      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	$statement = $pdo->query($sql);
	return $statement->fetchAll();
}

?>