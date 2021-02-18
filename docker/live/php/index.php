<?php
var_dump($_ENV);

echo "prova";

$servername = "dis-mto-staging-staging.cixy8npper5f.eu-central-1.rds.amazonaws.com";
$username = "adminuser";
$password = 'h9QOQKWwZ&7B';

try {
  $conn = new PDO("mysql:host=$servername;dbname=dis_mto_staging", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}