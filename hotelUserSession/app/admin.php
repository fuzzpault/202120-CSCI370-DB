<?php 
	session_start();
?>

<html>
<head>
</head>

<body>
<?php
  	
  	$dsn = 'mysql:host=localhost;dbname=hotels';
  	$username = 'root';
  	$password = '';
  	$pdo = new PDO($dsn, $username, $password);

  	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  	

	if(isset($_GET['username'])){
	  	$sql = 'SELECT userID '.
	  		   'FROM users ' .
	  		   'WHERE username = ? AND pw = ?;';

	  	$statement = $pdo->prepare($sql);
	  	$statement->bindValue(1, $_GET['username']);
	  	$statement->bindValue(2, $_GET['password']);
	  	try{
	  		$ret = $statement->execute();
	  	}catch(Exception $e){
	  		echo "Lookup error: ", $e->getMessage();
	  	}

	  	$row = $statement->fetch(PDO::FETCH_ASSOC);

	  	if($row === FALSE){
	  		echo "Wrong username or password.";
	  	}else{
	  		$_SESSION['userID'] = $row['userID'];
	  	}

	}
	if(isset($_GET['logout'])){
		echo "logout called";
		session_unset();
	}

	if(!isset($_SESSION['userID'])){
		include("login.html");
	}else{
		
		$sql = 'SELECT * '.
	  		   'FROM users ' .
	  		   'WHERE userId = ?;';

	  	$statement = $pdo->prepare($sql);
	  	$statement->bindValue(1, $_SESSION['userID']);
	  	try{
	  		$ret = $statement->execute();
	  	}catch(Exception $e){
	  		echo "Lookup error: ", $e->getMessage();
	  	}

	  	$row = $statement->fetch(PDO::FETCH_ASSOC);
	  	
	  	$username = $row['username'];
	  	echo "<h2>Welcome $username!</h2>";
		echo '<a href="admin.php?logout">Logout</a>';
	}
?>
</body>
</html>