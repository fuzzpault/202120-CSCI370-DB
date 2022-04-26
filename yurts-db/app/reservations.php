<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Reservations</title>
    <link href="/static/pacific.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <?php include 'static/menu.html'; ?>

    <?php 
      $dsn = 'mysql:host=localhost;dbname=yurt_reservations';
      $username = 'root';
      $password = '';
      $pdo = new PDO($dsn, $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    ?>

    <?php
      // Handle new reservation
      if(isset($_GET['begin']) && $_GET['begin'] > $_GET['end']){
        echo "<h3>Invalid dates.  Begin must be less than ending.</h3>";
      }else if(isset($_GET['begin'])){
        // See if the yurt is available during those dates
        // First see how many reservations there are for that yurt
        $sql = 'SELECT count(id) '.
          'FROM reservations '.
          'WHERE yurt_id = ?;';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $_GET['yurt_id']);
        try{
          $ret = $statement->execute();
        }catch(Exception $e){
          echo "Lookup error: ", $e->getMessage();
        }
        $num_reservations = $statement->fetch()[0];
        // See how many do not collide
        $sql = 'SELECT count(id) '.
          'FROM reservations '.
          'WHERE yurt_id = ? AND (? < begin_day OR end_day < ?);';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $_GET['yurt_id']);
        $statement->bindValue(2, $_GET['end']);
        $statement->bindValue(3, $_GET['begin']);
        
        try{
          $ret = $statement->execute();
        }catch(Exception $e){
          echo "Lookup error: ", $e->getMessage();
        }
        $num_clear = $statement->fetch()[0];

        // If the number of reservations for that yurt matches the number
        // of reservations that don't overlap - we're good!
        if($num_reservations == $num_clear){
          // enter the reservation!
          $sql = 'INSERT INTO reservations '.
         '(yurt_id, begin_day, end_day, num_guests, guest_name, guest_phone, secret) ' .
         'VALUES (?, ?, ?, ?, ?, ?, ?);';
          $statement = $pdo->prepare($sql);
          $statement->bindValue(1, $_GET['yurt_id']);
          $statement->bindValue(2, $_GET['begin']);
          $statement->bindValue(3, $_GET['end']);
          $statement->bindValue(4, $_GET['num_guests']);
          $statement->bindValue(5, $_GET['name']);
          $statement->bindValue(6, $_GET['phone']);
          $statement->bindValue(7, $_GET['secret']);
          try{
            $ret = $statement->execute();
          }catch(Exception $e){
            echo "Insert error: ", $e->getMessage();
          }
          echo "<h3>Reservation Made!</h3>";
          
          
        }else{
          echo "<h3>Time conflict!</h3>\n";
        }
      }
      // Handle deleted reservation
      if(isset($_GET['secret_del'])){
        //$pdo->query('SET SQL_SAFE_UPDATES = 0;');
        $sql =  'DELETE '.
          'FROM reservations '.
          'WHERE id <> 10000 AND secret = ?;';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $_GET['secret_del']);
        try{
          $ret = $statement->execute();
        }catch(Exception $e){
          echo "Lookup error: ", $e->getMessage();
        }

        if($statement->rowCount() == 1){
          echo "<h3>Delete successful</h3>";
        }else{
          echo "<h3>Reservation delete error - try again.</h3>";
        }
      }
    ?>

    
    <h2>Yurt Reservations</h2>
    
    <hr>
    <form method='GET'>
    <label>Choose a Yurt to view Availability</label>
    <select name="yurt_id">
    <?php
      $sql = 'SELECT id,name from yurts;';
      $statement = $pdo->query($sql);
      $yurt_ids = array();
      while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $yurt_ids[$row['id']] = $row['name'];
        //echo '    <option value="' . $row['id'] . '">' . $row['name'] . '</option><br>\n';
      }
      
      foreach($yurt_ids as $id => $name){
        echo '    <option value="' . $id . '">' . $name . '</option><br>';
        echo "\n";
      }

      
    ?>
    

    </select>
    <input type="submit" value="View" />
    </form>
    

    <?php
    if(!isset($_GET['yurt_id'])){
      echo '<h2>No yurt selected</h2>';
    }else{
      $sql = 'SELECT begin_day, end_day, name '.
          'FROM reservations r '.
          'INNER JOIN yurts y on y.id = r.yurt_id '.
          'WHERE yurt_id = ?;';
      $statement = $pdo->prepare($sql);
      $statement->bindValue(1, $_GET['yurt_id']);
    
      try{
        $ret = $statement->execute();
      }catch(Exception $e){
        echo "Lookup error: ", $e->getMessage();
      }
  
      $occupied = array_fill(0,36,0); // 1-35, ignore position 0
      $yurt_name = "Invalid";
      while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        for($i = $row['begin_day']; $i <= $row['end_day']; $i++){
          $occupied[$i] = 1;
        }
        $yurt_name = $row['name'];
      }
      echo "<br>Chosen Yurt: $yurt_name";
      echo '<table class="occupied">';
      echo '<tr>';
      echo '<th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th>';
      echo '<th>Saturday</th><th>Sunday</th></tr><tr>';
      for($i = 1; $i <= 35; $i++){
        if($occupied[$i] == 1){
          echo '<td class="taken">-' . $i .'-</td>';
        }else{
          echo '<td>' . $i .'</td>';
        }
        if($i % 7 == 0){
          echo '</tr><tr>';
        }
      }
      echo '</tr></table>';
    }

    ?>
    <hr>

    

    <h3>Reserve a Yurt</h3>
    <form method="GET">
      <label>Yurt:</label>
      <select name="yurt_id">
      <?php
        foreach($yurt_ids as $id => $name){
          echo '    <option value="' . $id . '">' . $name . '</option><br>';
          echo "\n";
        }
      ?>
      </select><br>

      <label>Beginning Day Number:</label>
      <input name="begin" type="text" /><br>

      <label>Ending Day Number:</label>
      <input name="end" type="text" /><br>

      <label>Number of guests:</label>
      <input name="num_guests" type="text" /><br>

      <label>Your full name:</label>
      <input name="name" type="text" /><br>

      <label>Your phone number (xxx-xxx-xxxx):</label>
      <input name="phone" type="text" /><br>

      <label>5 digit secret</label>
      <input name="secret" type="text" /><br>

      <input type="submit" value="Reserve Yurt!" />
    </form>

    <hr>

  
    <h3>Delete a Reservation</h3>
    <p>Enter the secret provided during registration to delete your reservation</p>
    <form method="GET">
      <label>Secret</label>
      <input name="secret_del" type="text" />

      <input type="submit" value="Delete Reservation" />
    </form>
    
    <?php include 'static/footer.html'; ?>
  </body>
</html>