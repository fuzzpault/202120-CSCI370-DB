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
     $mem  = new Memcached();

    $mem->addServer('host.docker.internal',11211);
    if( $mem->add("mystr","this is a memcache test!",3600)){
        echo  'Added!';
    }else{
        echo 'Already there：'.$mem->get("mystr");
    }
     echo "version: ",var_dump($mem->getVersion());
    ?>

    

    
    

  

    
    

    
    <?php include 'static/footer.html'; ?>
  </body>
</html>