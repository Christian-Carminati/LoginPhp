<?php
  $host = "localhost";
  $username = "carminati132";
  $password = '';
  $database = "my_carminati132";

  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
    $query = "CREATE TABLE IF NOT EXISTS users(
        ID int NOT NULL AUTO_INCREMENT,
        username varchar(25) NOT NULL,
        email varchar(50) NOT NULL,
        password char(64) NOT NULL,
        PRIMARY KEY (id)
       );";
    if(mysqli_query($conn, $query)){
       
    } else{
        echo "ERROR: gen tables";
    }
?>
