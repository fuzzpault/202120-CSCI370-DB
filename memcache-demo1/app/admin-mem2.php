<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Reservations</title>
    <link href="/static/pacific.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
  

    <?php 
      $pdo = FALSE;
      

      include("mem2.php");
    ?>

    <?php
      // Handle deleted reservation
      if(isset($_GET['id_del'])){
        //$pdo->query('SET SQL_SAFE_UPDATES = 0;');
        $sql =  'DELETE '.
          'FROM reservations '.
          'WHERE id = ?;';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $_GET['id_del']);
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

    
    <h2>All Reservations</h2>
    
    <table>
      <tr>
        <th>Yurt Name</th>
        <th>Guest Name</th>
        <th>Guest Phone</th>
        <th>Num Guests</th>
        <th>Begin Day</th>
        <th>End Day</th>
        <th>Price</th>
      </tr>
    <?php
      $sql = 'SELECT *, (y.cost * r.num_guests * (end_day - begin_day + 1)) '.
        'FROM reservations r '.
        'JOIN yurts y on r.yurt_id = y.id '.
        ' ORDER BY r.begin_day ASC; ';
      //$statement = $pdo->query($sql);
      $result = doQuery($mem, $pdo, $sql);
      //echo var_dump($result);
      //while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      foreach($result as $row){
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['guest_name'] . "</td>";
        echo "<td>" . $row['guest_phone'] . "</td>";
        echo "<td>" . $row['num_guests'] . "</td>";
        echo "<td>" . $row['begin_day'] . "</td>";
        echo "<td>" . $row['end_day'] . "</td>";
        echo "<td>" . $row['(y.cost * r.num_guests * (end_day - begin_day + 1))'] . "</td>";
        echo "</tr>";
      }
    ?>
    </table>

    <hr>
    <h2>Total Profit per Yurt</h2>
    <table>
      <tr>
        <th>Yurt Name</th>
        <th>Total Profit</th>
      </tr>
    <?php
      $sql = 'SELECT y.name, sum(y.cost * r.num_guests * (r.end_day - r.begin_day + 1)) '.
        'FROM reservations r '.
        'JOIN yurts y on r.yurt_id = y.id '.
        'GROUP BY y.id '.
        'ORDER BY y.id ASC ';
      //$statement = $pdo->query($sql);
      //while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      $result = doQuery($mem, $pdo, $sql);
      foreach($result as $row){
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row["sum(y.cost * r.num_guests * (r.end_day - r.begin_day + 1))"] . "</td>";
        echo "</tr>\n";
      }
    ?>
    </table>

    
    

    
  
  </body>
</html>