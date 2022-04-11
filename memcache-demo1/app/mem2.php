<?php   

$mem  = new Memcached();
      // List memcache servers
$mem->addServer('host.docker.internal',11211);

if($mem->getVersion() === FALSE){
        echo "<h2>Memcache server connection error</h2>";
}else{
	echo "<h2>connected to memcache</h2>";
}

function doQuery($mem, $pdo, $sql){
	$key = base64_encode($sql);
	$ret = $mem->get($key);
	if($ret !== FALSE){
		echo "<h2>Got $key from cache!</h2>";
		return $ret;
	}else{ // not in memcache!!!!!
		echo "<h2>No pizza in cache, making $key</h2>";
		if($pdo === FALSE){
			$dsn = 'mysql:host=localhost;dbname=yurt_reservations';
		      $username = 'root';
		      $password = '';
		      $pdo = new PDO($dsn, $username, $password);
		      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		$statement = $pdo->query($sql);
		$ret = $statement->fetchAll();
		$mem->set($key, $ret, 5);
		echo $mem->getResultMessage();
		return $ret;
	}
	
}

?>