<?php
try
{
  $pdo = new PDO('mysql:host=localhost;dbname=veroyori_pos', 'htolajide', 'olajide4me');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec('SET NAMES "utf8"');
}
catch (PDOException $e)
{
  $error = 'Unable to connect to the database server.'.$e;
  include 'error.html.php';
  exit();
}
