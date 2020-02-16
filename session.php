<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


if (session_status() == PHP_SESSION_NONE) {
session_start();
}
$sess = session_id();
     echo $sess;

try
{
$pdo->beginTransaction();
//⋮ perform a series of queries…
$pdo->commit();
}
catch (PDOException $e)
{
$pdo->rollBack();
$error = 'Error performing the transaction.';
include 'error.html.php';
exit();
}



?>
