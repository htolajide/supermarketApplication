<?php
error_reporting( E_ALL );
// ini_set( "display_errors", 1 );
include_once  '../../includes/magicquotes.inc.php';
require_once '../../includes/access.inc.php';

//if (session_status() == PHP_SESSION_NONE) {session_start();}
if (!userIsLoggedIn())
{
  include '../login.html.php';
  exit();
}
if (!userHasRole('Content Editor'))
{
	$error = 'Only Content Editors may access this page.';
	include '../accessdenied.html.php';
        //session_start();
        unset($_SESSION['loggedIn']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        //include '../login.html.php'; 
	exit();
}

try
  {
    include '../../includes/db.inc.php';
    $sql = 'SELECT name, branchid FROM users WHERE email = :email AND password = :password';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_SESSION['email']);
    $s->bindValue(':password', $_SESSION['password']);
    $s->execute();
    $row = $s->fetch();
  }
  catch (PDOException $e)
  {
      $error = 'Error searching for user name.';
    //include 'error.html.php';
    exit();
  }
$_SESSION['name'] = explode(" ",$row['name'])[0];
$welcome = 'Hi';
					if (date("H") <= 12) {
						$welcome = 'Good Morning';
					} else if (date('H') > 12 && date("H") < 18) {
						$welcome = 'Good Afternoon';
					} else if(date('H') > 17) {
						$welcome = 'Good Evening';
					}
				$greeting = $welcome.', '.$_SESSION['name'];
$_SESSION['branch']=$row['branchid'];
$_SESSION['saleid'] = session_id();
   //echo  $_SESSION['saleid'];    

//database Backup	
if(isset($_GET['backup'])){
	backUp();
	$message =  'Data Backup Successful, Please Copy Backup Folder From C drive To External Disk';
    $link = '.';
    include 'success.html.php';
	exit();
}
   

if (isset($_POST['action']) and $_POST['action'] == 'Credit')
{
    
  include  '../../includes/db.inc.php';
       $_SESSION['cart'][] = $_POST['id'];
       $_SESSION['moved'] = 0;
       $_SESSION['credit'] = 1;
         //header('Location: .');
         //exit();
  try
  {
    $sql = 'SELECT id, name, quantity, price, productdate, branchid, brandid, categoryid, userid FROM product WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching product details.';
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();

  $pageTitle = 'Enter Quantity and Select Credit to Supply Goods On Credit.';
  $action = 'creditform';
  $name = $row['name'];
  $quantity = $row['quantity'];
  $price = $row['price'];
  $brandid = $row['brandid'];
  $branchid = $row['branchid'];
  $categoryid = $row['categoryid'];
  $userid = $row['userid'];
  $productdate = $row['productdate']; 
  $credit = 0;
  $id= $row['id'];
  $button = 'Supply Good';
  
  // get the list of assigned category
 try
  {
    $sql = 'SELECT id FROM category WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $categoryid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of categorys.';
    include 'error.html.php';
    exit();
  }

 
  $selectedCategory = array();
  foreach ($s as $row)
  {
    $selectedCategory[] = $row['id'];
  }


  // Get list of user assigned to this product
  try
  {
    $sql = 'SELECT id FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $userid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedUser = array();
  foreach ($s as $row)
  {
    $selectedUser[] = $row['id'];
  }
  // Get list of brand assigned to this product
  try
  {
    $sql = 'SELECT id FROM brand WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $brandid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedBrand = array();
  foreach ($s as $row)
  {
    $selectedBrand[] = $row['id'];
  }
  // Get list of source assigned to this product
  try
  {
    $sql = 'SELECT id FROM branch WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $branchid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of sources roles.';
    include 'error.html.php';
    exit();
  }

  $selectedSources = array();
  foreach ($s as $row)
  {
    $selectedSources[] = $row['id'];
  }
  // Get list of destination to this product
  try
  {
    $sql = 'SELECT id FROM branch WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $branchid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of sources roles.';
    include 'error.html.php';
    exit();
  }

  $selectedDestinations = array();
  foreach ($s as $row)
  {
    $selectedDestinations[] = $row['id'];
  }

  // Build the list of all category
  try
  {
    $result = $pdo->query('SELECT id, name FROM category order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of roles.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $categories[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedCategory));
  }
 // Build the list of all sources
  try
  {
    $result = $pdo->query('SELECT id, name FROM branch order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $sources[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedSources));
  }
 // Build the list of all destinations
  try
  {
    $result = $pdo->query('SELECT id, name FROM branch order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $destinations[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedDestinations)    
    );
  }
 
 // Build the list of all users
  try
  {
    $result = $pdo->query('SELECT id, name FROM users order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $users[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedUser));
  }
 // Build the list of all brands
  try
  {
    $result = $pdo->query('SELECT id, name FROM brand order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $brands[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedBrand));
  }
 
  include 'form.html.php';
  exit();
}

if (isset($_GET['creditform']))
{
  include  '../../includes/db.inc.php';
  
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
  
  try
  {
    $sql = 'SELECT quantity FROM product WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching product details.';
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();
  $check_quantity = $row['quantity'];
  if($quantity > $check_quantity ){
      $error= 'The requested quantity of product is more than available quantity '. $check_quantity;
       include 'error.html.php';
	   exit();
  } 

  else{
      
   
	$pattern = "/^\d+$/";
	//Input Validations
		
	if (!preg_match($pattern,$quantity)){
	$error = "Quantity must be whole number";	
	include 'error.html.php';
    exit();
   }
   else{
   $cid= rand(100,999);
        //echo $cid;
try
{
$pdo->beginTransaction();
//⋮ perform a series of queries
       $sql = 'INSERT INTO sales SET
        price = :price,
        productid = :productid,
        userid = :userid,
        brandid = :brandid,
        customerid = :customerid,
        categoryid = :categoryid,
        sourceid = :sourceid,
        destinationid = :destinationid,
        saledate = CURDATE(),
        saleid = :saleid,
        credit = :credit,
        quantity = :quantity';
       
    $s = $pdo->prepare($sql);
    $s->bindValue(':productid',$_POST['id']);
    $s->bindValue(':userid', $_POST['users']);
    $s->bindValue(':price', $price);
    $s->bindValue(':quantity', $quantity);
    $s->bindValue(':brandid', $_POST['brands']);
    $s->bindValue(':customerid', $cid);
    $s->bindValue(':categoryid',$_POST['categories']);
    $s->bindValue(':sourceid', $_POST['sources']);
    $s->bindValue(':credit', $_POST['credit']);
    $s->bindValue(':saleid', $_SESSION['saleid']);
    //$s->bindValue(':date', $_POST['salesdate']);
    $s->bindValue(':destinationid', $_POST['destinations']);
    $s->execute();
    $sql = 'UPDATE product SET quantity=quantity-:quantity WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':quantity',$_POST['quantity']);
    $s->execute();
    //header('Location: .');
    $message = 'Supply on Credit Successful Continue Credit or';
    $link = '.';
    include 'success.html.php';
    //echo $message;
    $pdo->commit();
    //exit();
    }
    catch (PDOException $e)
    {
    $pdo->rollBack();
    $error = 'Error performing the transaction of supplying item'.$e;
    include 'error.html.php';
    //$GLOBALS['traker'] =1;
    exit();
    }
    }
}
}

// save transactions
if (isset($_POST['action']) and $_POST['action'] == 'Submit Transaction')
{
  include  '../../includes/db.inc.php';
  //$_SESSION['customername'] =  $_POST['customername'];
  $customername=strtoupper($_POST['customername']);
  if(isset($_POST['customername']) &&  $_POST['customername'] !=""){
  try{
		$sql = 'INSERT INTO transactions SET
			transactionnumber = :transactionnumber,
			customername = :customername,
			moved = :moved,
			credit = :credit,
			tdate = CURDATE() ';
		$s = $pdo->prepare($sql);
		$s->bindValue(':transactionnumber', $_SESSION['saleid']);
		$s->bindValue(':moved', $_SESSION['moved']);
		$s->bindValue(':credit', $_SESSION['credit']);
		//$s->bindValue(':date', $_POST['salesdate']);
		$s->bindValue(':customername', $customername);
		$s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error submitting transcation'.$e;
    include 'error.html.php';
    exit();
  }}else{ echo "Please Enter Customer Name";
      exit();
  }
  $message = 'Transaction Successful';
    $link = '.';
    session_regenerate_id();    
    unset($_SESSION['cart']);
    include 'success.html.php';
    //echo $message;
    //exit();
}

//pay credit
if (isset($_POST['action']) and $_POST['action'] == 'Pay Credit'){
    include  '../../includes/db.inc.php';
    
      try
  {
    $sql = 'UPDATE transactions set tdate=CURDATE(), credit=0 WHERE transactionnumber=:number';
    $s = $pdo->prepare($sql);
    $s->bindValue(':number', $_POST['transactionid']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error Updating credit';
    include 'error.html.php';
    exit();
  }
    try
  {
    $sql = 'UPDATE sales set saledate=CURDATE(), credit=0 WHERE saleid=:number';
    $s = $pdo->prepare($sql);
    $s->bindValue(':number', $_POST['transactionid']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error Updating credit';
    include 'error.html.php';
    exit();
  }
  $message = 'Credit has been cleared, go to control panel and print paid receipt from transaction menu';
    $link = '.';
    include 'success.html.php';
    //exit();
}
//load other transactions
if (isset($_POST['action']) and $_POST['action'] == 'See Transactions'){
    include  '../../includes/db.inc.php';
    $pagetitle = "Transactions";
    $title= "Transactions On ";
    $action ="See Receipt";
    if (isset($_POST['tdate'])){
      try
  {
    $sql = 'SELECT id, transactionnumber,customername FROM transactions WHERE tdate = :tdate order by id DESC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':tdate', $_POST['tdate']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching transactions.';
    include 'error.html.php';
    exit();
  }
  }else{ echo "Please enter a date";}
  if(isset($s)){
  foreach ($s as $row)
  {
    
     $transacts[] = array( 'id' => $row['id'], 'customername' => $row['customername'], 'transactionnumber' => $row['transactionnumber']);
      
  }
  }else {echo 'No transaction found';}
 include 'transactionrecord.html.php';
 exit();
}

//load other credit transactions
if (isset($_POST['action']) and $_POST['action'] == 'See Credit'){
    include  '../../includes/db.inc.php';
    $pagetitle = "Credit ";
    $title= "Credits On ";
    $action ="Credit Detail";
    if (isset($_POST['tdate'])){
      try
  {
    $sql = 'SELECT id, transactionnumber,customername FROM transactions WHERE tdate = :tdate and credit=1 order by id DESC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':tdate', $_POST['tdate']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching transactions.';
    include 'error.html.php';
    exit();
  }
  }else{ echo "Please enter a date";}
  if(isset($s)){
  foreach ($s as $row)
  {
    
     $transacts[] = array( 'id' => $row['id'], 'customername' => $row['customername'], 'transactionnumber' => $row['transactionnumber']);
      
  }
  }else {echo 'No Credit found';}
 include 'transactionrecord.html.php';
 exit();
}

//load other Moved transactions
if (isset($_POST['action']) and $_POST['action'] == 'See Moved'){
    include  '../../includes/db.inc.php';
    $pagetitle = "Moved Goods";
    $title= "Moved Goods On ";
    $action ="Credit Detail";
    if (isset($_POST['tdate'])){
      try
  {
    $sql = 'SELECT id, transactionnumber,customername FROM transactions WHERE tdate = :tdate and moved=1 order by id DESC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':tdate', $_POST['tdate']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching transactions.';
    include 'error.html.php';
    exit();
  }
  }else{ echo "Please enter a date";}
  if(isset($s)){
  foreach ($s as $row)
  {
    
     $transacts[] = array( 'id' => $row['id'], 'customername' => $row['customername'], 'transactionnumber' => $row['transactionnumber']);
      
  }
  }else {echo 'Moved goods not found';}
 include 'transactionrecord.html.php';
 exit();
}


if (isset($_POST['action']) and $_POST['action'] == 'Sell')
{
  include  '../../includes/db.inc.php';
      $_SESSION['cart'][] = $_POST['id'];
      $_SESSION['moved'] = 0;
      $_SESSION['credit'] = 0;
         //header('Location: .');
         //exit();
  try
  {
    $sql = 'SELECT id, name, quantity, price, productdate, branchid, brandid, categoryid, userid FROM product WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching product details.';
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();

  $pageTitle = 'Enter Quantity To Sell Product.';
  $action = 'saleform';
  $name = $row['name'];
  $quantity = $row['quantity'];
  $price = $row['price'];
  $brandid = $row['brandid'];
  $branchid = $row['branchid'];
  $categoryid = $row['categoryid'];
  $userid = $row['userid'];
  $productdate = $row['productdate']; 
  $credit = 0;
  $id= $row['id'];
  $button = 'Sell Item';
  
  // get the list of assigned category
 try
  {
    $sql = 'SELECT id FROM category WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $categoryid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of categorys.';
    include 'error.html.php';
    exit();
  }

 
  $selectedCategory = array();
  foreach ($s as $row)
  {
    $selectedCategory[] = $row['id'];
  }


  // Get list of user assigned to this product
  try
  {
    $sql = 'SELECT id FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $userid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedUser = array();
  foreach ($s as $row)
  {
    $selectedUser[] = $row['id'];
  }
  // Get list of brand assigned to this product
  try
  {
    $sql = 'SELECT id FROM brand WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $brandid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedBrand = array();
  foreach ($s as $row)
  {
    $selectedBrand[] = $row['id'];
  }
  // Get list of source assigned to this product
  try
  {
    $sql = 'SELECT id FROM branch WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $branchid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of sources roles.';
    include 'error.html.php';
    exit();
  }

  $selectedSources = array();
  foreach ($s as $row)
  {
    $selectedSources[] = $row['id'];
  }
  // Get list of destination to this product
  try
  {
    $sql = 'SELECT id FROM branch WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $branchid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of sources roles.';
    include 'error.html.php';
    exit();
  }

  $selectedDestinations = array();
  foreach ($s as $row)
  {
    $selectedDestinations[] = $row['id'];
  }

  // Build the list of all category
  try
  {
    $result = $pdo->query('SELECT id, name FROM category order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of roles.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $categories[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedCategory));
  }
 // Build the list of all sources
  try
  {
    $result = $pdo->query('SELECT id, name FROM branch order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $sources[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedSources));
  }
 // Build the list of all destinations
  try
  {
    $result = $pdo->query('SELECT id, name FROM branch order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $destinations[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedDestinations)    
    );
  }
 
 // Build the list of all users
  try
  {
    $result = $pdo->query('SELECT id, name FROM users order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $users[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedUser));
  }
 // Build the list of all brands
  try
  {
    $result = $pdo->query('SELECT id, name FROM brand order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $brands[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedBrand));
  }
 
  include 'form.html.php';
  exit();
}


if (isset($_GET['saleform']))
{
  include  '../../includes/db.inc.php';
  
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
  
  try
  {
    $sql = 'SELECT quantity FROM product WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching product details.';
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();
  $check_quantity = $row['quantity'];
  if($quantity > $check_quantity ){
      $error= 'The requested quantity of product is more than available quantity '. $check_quantity;
       include 'error.html.php';
	   exit();
  } 

  else{
	//$pattern = "/^\d+$/";
	//Input Validations
	if ($quantity < 1){
		$removedId = array_pop($_SESSION[cart]);	
		$error = "Quantity must be whole number";
		include 'error.html.php';
		exit();
   }
   else{
		$cid= rand(100,999);
        //echo $cid;
try
{
$pdo->beginTransaction();
//⋮ perform a series of queries
       $sql = 'INSERT INTO sales SET
        price = :price,
        productid = :productid,
        userid = :userid,
        brandid = :brandid,
        customerid = :customerid,
        categoryid = :categoryid,
        sourceid = :sourceid,
        destinationid = :destinationid,
        saledate = CURDATE(),
        saleid = :saleid,
        quantity = :quantity';
    $s = $pdo->prepare($sql);
    $s->bindValue(':productid',$_POST['id']);
    $s->bindValue(':userid', $_POST['users']);
    $s->bindValue(':price', $price);
    $s->bindValue(':quantity', $quantity);
    $s->bindValue(':brandid', $_POST['brands']);
    $s->bindValue(':customerid', $cid);
    $s->bindValue(':categoryid',$_POST['categories']);
    $s->bindValue(':sourceid', $_POST['sources']);
    $s->bindValue(':saleid', $_SESSION['saleid']);
    //$s->bindValue(':date', $_POST['salesdate']);
    $s->bindValue(':destinationid', $_POST['destinations']);
    $s->execute();
    $sql = 'UPDATE product SET quantity=quantity-:quantity WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':quantity',$_POST['quantity']);
    $s->execute();
    //header('Location: .');
    $message = 'Sale Successful, Continue selling or ';
    $link = '.';
    include 'success.html.php';
    //echo $message;
    $pdo->commit();
    //exit();
    }
    catch (PDOException $e)
    {
    $pdo->rollBack();
    $error = 'Error performing the transaction of selling item'.$e;
    include 'error.html.php';
    //$GLOBALS['traker'] =1;
    exit();
    }
    }
}
}
 
 // Total quantity Sold/moved
if (isset($_POST['action']) and $_POST['action'] == 'Quantity Profile')
{
 include  '../../includes/db.inc.php';

try
{
	//quantity sold
    $sql= 'Select sum(quantity) as qsold from sales where productid=:id and sourceid=:branchid and saledate=:date and credit=0 ';
    $s = $pdo->prepare($sql);
    $s->bindValue(':date', $_POST['date']);
	$s->bindValue(':id', $_POST['pid']);
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $qsold = $row['qsold'];
        } 
		if($qsold ==''){
			$qsold=0;
		}
    } 
	//quantity on credit
	$sql= 'Select sum(quantity) as qcredit from sales where productid=:id and sourceid=:branchid and saledate=:date and credit=1 ';
    $s = $pdo->prepare($sql);
    $s->bindValue(':date', $_POST['date']);
	$s->bindValue(':id', $_POST['pid']);
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $qcredit = $row['qcredit'];
        } 
		if($qcredit ==''){
			$qcredit=0;
		}
    } 
	//quantity moved
	 $sql= 'Select sum(quantity) as qmoved from movement where productid=:id and sourceid=:branchid and movedate=:date ';
    $s = $pdo->prepare($sql);
    $s->bindValue(':date', $_POST['date']);
	$s->bindValue(':id', $_POST['pid']);
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $qmoved = $row['qmoved'];
        } 
		if($qmoved ==''){
			$qmoved=0;
		}
    } 	
	
	//quantity received
	 $sql= 'Select sum(quantity) as qreceived from movement where productid=:id and destinationid=:branchid and movedate=:date ';
    $s = $pdo->prepare($sql);
    $s->bindValue(':date', $_POST['date']);
	$s->bindValue(':id', $_POST['pid']);
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $qreceived = $row['qreceived'];
        } 
		if($qreceived ==''){
			$qreceived=0;
		}
    } 	
	$total=($qmoved+$qcredit+$qsold+$qreceived);
	$message = 'Quantity Profile for Selected Item: Quantity Sold is '.$qsold.'| Quantity Moved is '.$qmoved.'| Quantity Received is '.$qreceived.'| Quantity on Credit is '.$qcredit.'| Total is '.$total ;
	$link ='.';
    include 'success.html.php';
    //exit();
}catch(PDOException $e){
    $error = "Unable to fetch quantity".$e;
    include 'error.html.php';
}
    
}

// Undo sale records
if (isset($_POST['action']) and $_POST['action'] == 'Undo Sale')
{
 include  '../../includes/db.inc.php';

try
{
    $pdo->beginTransaction();
  
    $sql = 'Update product set quantity = quantity+:quantity where id =:id';
 
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['pid']);
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->execute();
    
    $sql = 'DELETE FROM sales WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['sid']);
    $s->execute();
    $pdo->commit();
    $message = "Sale undo successfully";
	$link = '?sales';
    include 'success.html.php';
    //exit();
    
   }
catch (PDOException $e)
{
  $pdo->rollBack();
  $error = 'Error deleting sales from the database!'.$e;
  include 'error.html.php';
  exit();
}
}

//remove item
if (isset($_POST['action']) and $_POST['action'] == 'Reset Selected Items')
          {
            // Empty the $_SESSION['cart'] array
            unset($_SESSION['cart']);
            header('Location:.');
             exit();
          }
          
 // Load searched sales Record     
          if (isset($_POST['action']) and $_POST['action'] == 'Submit')
    {
        // Display product list
include  '../../includes/db.inc.php';

try
{
  
    	$alpha = $_POST['productname'] ;
  $sql = "SELECT product.id as pid, product.name as pname, quantity, price, productdate, users.name as username, branch.name as branchname, category.name as categoryname, brand.name as brandname from product inner join users on users.id=userid inner join branch
      on branch.id=product.branchid inner join category on category.id=categoryid inner join brand on brand.id=brandid where  product.branchid = :branchid and product.name LIKE'".$alpha."%' order by product.name";
 
 
    $s = $pdo->prepare($sql);
    //$s->bindValue(':productname', $_POST['productname']);
    $s->bindValue(':branchid', $_SESSION['branch']);
    $s->execute();
   
}
catch (PDOException $e)
{
  $error = 'Error fetching products from the database!'.$e;
  include 'error.html.php';
  exit();
}

foreach ($s as $row)
{
  $products[] = array('pid' => $row['pid'], 'pname' => $row['pname'], 'quantity' => $row['quantity'], 'price' => $row['price'], 'productdate' => $row['productdate'],'username' => $row['username'], 'branchname' => $row['branchname'], 'categoryname' => $row['categoryname'], 'brandname' => $row['brandname']);
} 

try{
    include  '../../includes/db.inc.php';
    
    $sql= 'Select sum( price*quantity) as total, price, quantity from sales where saledate=CURDATE() and sourceid=:sourceid';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':date', 2017-12-03);
    $s->bindValue(':sourceid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $todaySales = $row['total'];
        } 
    } 
    
}catch(PDOException $e){
    $error = "Unable to fetch today sales total".$e;
    include 'error.html.php';
}

try{
    include  '../../includes/db.inc.php';
    
    $sql= 'Select count(id) as productcount from product  where branchid=:branchid';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':date', 2017-12-03);
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $productcount = $row['productcount'];
        } 
    } 
    
}catch(PDOException $e){
    $error = "Unable to fetch count".$e;
    include 'error.html.php';
}

try{
    include  '../../includes/db.inc.php';
    
    $sql= 'Select count(id) as finishcount from product  where quantity <=0 and branchid=:branchid';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':date', 2017-12-03);
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $finishcount = $row['finishcount'];
        } 
    } 
    
}catch(PDOException $e){
    $error = "Unable to fetch count".$e;
    include 'error.html.php';
}
 include 'products.html.php';
 exit();
    }
// Load searched sales Record     
if (isset($_POST['action']) and $_POST['action'] == 'Search')
{
  try
{
    if (isset($_POST['date'])){
        
    include  '../../includes/db.inc.php';
    $sql = 'SELECT sales.id as sid, sales.customerid as customerid, product.name as pname,product.id as pid, sales.quantity as quantity, sales.price as unitprice, saledate, users.name as username, branch.name as branchname, category.name as categoryname, brand.name as brandname from sales inner join users on users.id=sales.userid inner join branch
      on branch.id=sales.sourceid inner join category on category.id=sales.categoryid inner join brand on brand.id=sales.brandid inner join product on product.id = sales.productid where sales.sourceid = :branchid and saledate = :saledate ';
 
    $s = $pdo->prepare($sql);
    $s->bindValue(':branchid', $_SESSION['branch']);
   // $s->bindValue(':saledate', 2018-01-18);
    $s->bindValue(':saledate', $_POST['date']);
    } else{ echo "Please enter a date";}
    if ($s->execute()){
        
    foreach ($s as $row)
{
  $sales[] = array('sid' => $row['sid'], 'pid' => $row['pid'], 'pname' => $row['pname'], 'quantity' => $row['quantity'], 'unitprice' => $row['unitprice'], 'saledate' => $row['saledate'], 'username' => $row['username'], 'branchname' => $row['branchname'], 'categoryname' => $row['categoryname'], 'brandname' => $row['brandname'], 'customerid' => $row['customerid']  );
} 

   $sql= 'Select sum(price*quantity) as total, count(id) as itemcount from sales where saledate=:saledate and sourceid=:sourceid';
    $s = $pdo->prepare($sql);
    $s->bindValue(':saledate', $_POST['date']);
    $s->bindValue(':sourceid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $daytotal = $row['total'];
            $itemcount = $row['itemcount'];
        } 
    }
include 'salesrecord.html.php';
exit();
}else {echo 'no record found';}
}
catch (PDOException $e)
{
  $error = 'Error fetching sales from the database!'.$e;
  include 'error.html.php';
  exit();
}

}

// Print receipt for a transaction
if (isset($_POST['action']) and $_POST['action'] == 'See Receipt'){
  try
{
      include  '../../includes/db.inc.php';
      $pagetitle="Cash Receipt";

  $sql = 'SELECT sales.id as sid, brand.name as brandname, product.name as productname, category.name as categoryname, users.phone as phonenumber,
      branch.name as branchname, sales.quantity as quantity, sales.price as unitprice, sales.quantity*sales.price
      as total, product.id as pid, sales.customerid as receiptno, sales.saledate as date FROM sales inner join brand on brand.id = sales.brandid inner join product on product.id = sales.productid
      inner join category on category.id = sales.categoryid inner join users on users.id=sales.userid inner join branch on branch.id = sales.sourceid  where sales.saleid =:saleid and sales.credit=0';
 
    $s = $pdo->prepare($sql);
    $s->bindValue(':saleid', $_POST['transactionid']);
    //$s->bindValue(':productid',$_POST['id']);
    $s->execute();
}
catch (PDOException $e)
{
  $error = 'Error fetching sales from the database!'.$e;
  include 'error.html.php';
  exit();
}
 $items = array();
foreach ($s as $row)
{
  $sales[] = array('pid' => $row['pid'],'sid' => $row['sid'],'brandname' => $row['brandname'], 'productname' => $row['productname'], 'categoryname' => $row['categoryname'], 'branchname' => $row['branchname'], 'unitprice' => $row['unitprice'],'quantity' => $row['quantity'], 'total' => $row['total'], 'receiptno' => $row['receiptno'],'date' => $row['date'], 'phonenumber' => $row['phonenumber']);
      array_push($items,$row['pid']);
    } 
     //$items = $sale['pid'];
     $cart = array();
     $total = 0;
           //$subtotal=0; 
           foreach ($items as $id)
          {
           foreach ($sales as $product)
           {
              if ($product['pid'] == $id)
           {
              $cart[] = $product;
              $total += $product['total'];
             break;
           }
         }
       } 
        $_SESSION['branchname']=$row['branchname'];
        $_SESSION['phonenumber']=$row['phonenumber'];
        $_SESSION['payment']="Cash Paid";
        $back='.';
       
       try{
 $sql = 'SELECT id, customername, tdate FROM transactions WHERE transactionnumber = :tnumber';
    $s = $pdo->prepare($sql);
    $s->bindValue(':tnumber', $_POST['transactionid']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching transactions.';
    include 'error.html.php';
    exit();
  }
  
  foreach ($s as $row)
  {
    
     $transacts[] = array( 'id' => $row['id'], 'customername' => $row['customername'], 'tdate' => $row['tdate'] );
      
  }
   include 'invoice.html.php';
   exit();
  }
  
  
  // Print receipt for a credit
if (isset($_POST['action']) and $_POST['action'] == 'Credit Detail'){
  try
{
      include  '../../includes/db.inc.php';
   $pagetitle="Credit Receipt";
  $sql = 'SELECT sales.id as sid, brand.name as brandname, product.name as productname, category.name as categoryname, users.phone as phonenumber,
      branch.name as branchname, sales.quantity as quantity, sales.price as unitprice, sales.quantity*sales.price
      as total, product.id as pid, sales.customerid as receiptno, sales.saledate as date FROM sales inner join brand on brand.id = sales.brandid inner join product on product.id = sales.productid
      inner join category on category.id = sales.categoryid inner join users on users.id=sales.userid inner join branch on branch.id = sales.sourceid  where sales.saleid =:saleid and sales.credit=1';
 
    $s = $pdo->prepare($sql);
    $s->bindValue(':saleid', $_POST['transactionid']);
    //$s->bindValue(':productid',$_POST['id']);
    $s->execute();
}
catch (PDOException $e)
{
  $error = 'Error fetching sales from the database!'.$e;
  include 'error.html.php';
  exit();
}
 $items = array();
foreach ($s as $row)
{
  $sales[] = array('pid' => $row['pid'],'sid' => $row['sid'],'brandname' => $row['brandname'], 'productname' => $row['productname'], 'categoryname' => $row['categoryname'], 'branchname' => $row['branchname'], 'unitprice' => $row['unitprice'],'quantity' => $row['quantity'], 'total' => $row['total'], 'receiptno' => $row['receiptno'],'date' => $row['date'], 'phonenumber' => $row['phonenumber']);
      array_push($items,$row['pid']);
    } 
     //$items = $sale['pid'];
     $cart = array();
     $total = 0;
           //$subtotal=0; 
           foreach ($items as $id)
          {
           foreach ($sales as $product)
           {
              if ($product['pid'] == $id)
           {
              $cart[] = $product;
              $total += $product['total'];
             break;
           }
         }
       } 
        $_SESSION['branchname']=$row['branchname'];
        $_SESSION['phonenumber']=$row['phonenumber'];
        $_SESSION['payment']="Cash not Paid";
        $back = '.';
       
       try{
 $sql = 'SELECT id, customername, tdate FROM transactions WHERE transactionnumber = :tnumber and credit=1 ';
    $s = $pdo->prepare($sql);
    $s->bindValue(':tnumber', $_POST['transactionid']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching transactions.';
    include 'error.html.php';
    exit();
  }
  
  foreach ($s as $row)
  {
    
     $transacts[] = array( 'id' => $row['id'], 'customername' => $row['customername'], 'tdate' => $row['tdate'] );
      
  }
   include 'invoice.html.php';
   exit();
  }
  
  // Print sheet for a movement
if (isset($_POST['action']) and $_POST['action'] == 'Moved Detail'){
  try
{
      include  '../../includes/db.inc.php';

  $sql = 'SELECT movement.id as mid, brand.name as brandname, product.name as productname, category.name as categoryname, users.phone as phonenumber,
      branch.name as source, movement.quantity as quantity, product.id as pid, movement.destinationid as destination, movement.movedate as date FROM movement inner join brand on brand.id = movement.brandid inner join product on product.id = movement.productid
      inner join category on category.id = movement.categoryid inner join users on users.id=movement.userid inner join branch on branch.id = movement.sourceid where movement.saleid =:saleid';
 
    $s = $pdo->prepare($sql);
    $s->bindValue(':saleid', $_POST['transactionid']);
    //$s->bindValue(':productid',$_POST['id']);
    $s->execute();
}
catch (PDOException $e)
{
  $error = 'Error fetching sales from the database!'.$e;
  include 'error.html.php';
  exit();
}
 $items = array();
foreach ($s as $row)
{
  $moves[] = array('pid' => $row['pid'],'mid' => $row['mid'],'brandname' => $row['brandname'], 'productname' => $row['productname'], 'categoryname' => $row['categoryname'], 'source' => $row['source'], 'quantity' => $row['quantity'], 'destination' => $row['destination'], 'date' => $row['date'], 'phonenumber' => $row['phonenumber']);
      array_push($items,$row['pid']);
    } 
     //$items = $sale['pid'];
     $cart = array();
     $total = 0;
           //$subtotal=0; 
           foreach ($items as $id)
          {
           foreach ($moves as $product)
           {
              if ($product['pid'] == $id)
           {
              $cart[] = $product;
              $total += 1;
             break;
           }
         }
       } 
        
        $_SESSION['phonenumber']=$row['phonenumber'];
        $_SESSION['source']=$row['source'];
        $_SESSION['destination']=$row['destination'];
        $_SESSION['payment']="Items of goods moved";
        $back = '.';
        
         try{
 $sql = 'SELECT name FROM branch WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_SESSION['destination']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching branch.';
    include 'error.html.php';
    exit();
  }
    $row=$s->fetch();
    $_SESSION['destination']=$row['name'];;  
       try{
 $sql = 'SELECT id, customername, tdate FROM transactions WHERE transactionnumber = :tnumber';
    $s = $pdo->prepare($sql);
    $s->bindValue(':tnumber', $_POST['transactionid']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching transactions.';
    include 'error.html.php';
    exit();
  }
  
  foreach ($s as $row)
  {
    
     $transacts[] = array( 'id' => $row['id'], 'customername' => $row['customername'], 'tdate' => $row['tdate'] );
      
  }
   include 'movementsheet.html.php';
   exit();
  }
  
  //load transactions
if (isset($_GET['transactions'])){
    include  '../../includes/db.inc.php';
    $pagetitle = "Today Transactions";
    $title = "Transactions";    
    $search = "See Transactions";
    $action = "See Receipt";
      try
  {
    $sql = 'SELECT id, transactionnumber,customername FROM transactions WHERE tdate = CURDATE() and moved = 0 and credit = 0 order by id DESC';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':id', $userid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching transactions.';
    include 'error.html.php';
    exit();
  }

  foreach ($s as $row)
  {
      if(isset($s)){
     $transacts[] = array( 'id' => $row['id'], 'customername' => $row['customername'], 'transactionnumber' => $row['transactionnumber']);
      }else {echo 'No transaction found';}
  }
 include 'transaction.html.php';
 exit();
}

//load credits
if (isset($_GET['credit'])){
    include  '../../includes/db.inc.php';
    $pagetitle = "Today Credit";
     $title = "Credit Record";   
    $search = "See Credit";
    $action = "Credit Detail";
      try
  {
    $sql = 'SELECT id, transactionnumber,customername FROM transactions WHERE tdate = CURDATE() and credit=1  order by id DESC';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':id', $userid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching transactions.';
    include 'error.html.php';
    exit();
  }

  foreach ($s as $row)
  {
      if(isset($s)){
     $transacts[] = array( 'id' => $row['id'], 'customername' => $row['customername'], 'transactionnumber' => $row['transactionnumber']);
      }else {echo 'No Credit Found';}
  }
 include 'transaction.html.php';
 exit();
}

//load moved 
if (isset($_GET['moved'])){
    include  '../../includes/db.inc.php';
    $pagetitle = "Today Moved";
    $title = "Moved Record";   
    $search = "See Moved";
    $action = "Moved Detail";
      try
  {
    $sql = 'SELECT id, transactionnumber,customername FROM transactions WHERE tdate = CURDATE() and moved=1 order by id DESC';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':id', $userid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching transactions.';
    include 'error.html.php';
    exit();
  }

  foreach ($s as $row)
  {
      if(isset($s)){
     $transacts[] = array( 'id' => $row['id'], 'customername' => $row['customername'], 'transactionnumber' => $row['transactionnumber']);
      }else {echo 'Moved not found';}
  }
 include 'transaction.html.php';
 exit();
} 


// move item to another branch
if (isset($_POST['action']) and $_POST['action'] == 'Move')
{
   //echo 'this is for testing';
    include  '../../includes/db.inc.php';
    $_SESSION['cart'][] = $_POST['id'];
    $_SESSION['moved'] = 1;
    $_SESSION['credit'] = 0;

  try
  {
    $mysql = 'SELECT id, name, quantity, price, productdate, branchid, brandid, categoryid, userid FROM product WHERE id = :id';
    $s = $pdo->prepare($mysql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching product details.';
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();

  $pageTitle = 'Enter Quantity And Select Destination To Move Product.';
  $action = 'moveform';
  $name = $row['name'];
  $quantity = $row['quantity'];
  $price = 0.00;
  $brandid = $row['brandid'];
  $branchid = $row['branchid'];
  $categoryid = $row['categoryid'];
  $userid = $row['userid'];
  $productdate = $row['productdate'];
  $credit=0; //set payment mode
  $id= $row['id'];
  $button = 'Move';
 
  // get the list of assigned category
 try
  {
    $sql = 'SELECT id FROM category WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $categoryid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of categorys.';
    include 'error.html.php';
    exit();
  }

 
  $selectedCategory = array();
  foreach ($s as $row)
  {
    $selectedCategory[] = $row['id'];
  }


  // Get list of user assigned to this product
  try
  {
    $sql = 'SELECT id FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $userid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedUser = array();
  foreach ($s as $row)
  {
    $selectedUser[] = $row['id'];
  }
  // Get list of brand assigned to this product
  try
  {
    $sql = 'SELECT id FROM brand WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $brandid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedBrand = array();
  foreach ($s as $row)
  {
    $selectedBrand[] = $row['id'];
  }
  // Get list of source assigned to this product
  try
  {
    $sql = 'SELECT id FROM branch WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $branchid);//$_SESSION['branch']
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of sources roles.';
    include 'error.html.php';
    exit();
  }

  $selectedSources = array();
  foreach ($s as $row)
  {
    $selectedSources[] = $row['id'];
  }
  // Get list of destination to this product
  try
  {
    $sql = 'SELECT id FROM branch WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $branchid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of sources roles.';
    include 'error.html.php';
    exit();
  }

  $selectedDestinations = array();
  foreach ($s as $row)
  {
    $selectedDestinations[] = $row['id'];
  }

  // Build the list of all category
  try
  {
    $result = $pdo->query('SELECT id, name FROM category order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of roles.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $categories[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedCategory));
  }
  
  //Build the sources
  try
  {
    $result = $pdo->query('SELECT id, name FROM branch order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $sources[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedSources));
  }
  
  //Build the list of all destinations
  try
  {
    $result = $pdo->query('SELECT id, name FROM branch order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $destinations[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedDestinations)    
    );
  }
 
 // Build the list of all users
  try
  {
    $result = $pdo->query('SELECT id, name FROM users');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $users[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedUser));
  }
 // Build the list of all brands
  try
  {
    $result = $pdo->query('SELECT id, name FROM brand order by name');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $brands[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedBrand));
  }
 
  include 'form.html.php';
  exit();
}


if (isset($_GET['moveform']))
{
  include  '../../includes/db.inc.php';
  
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
  try
  {
    $sql = 'SELECT quantity FROM product WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching product details.';
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();
  $check_quantity = $row['quantity'];
  if($quantity > $check_quantity ){
      $error= 'The requested quantity of product is more than available quantity '. $check_quantity;
       include 'error.html.php';
	   exit();
  }
  else{
      
   
	$pattern = "/^\d+$/";
	//Input Validations
		
	if (!preg_match($pattern,$quantity)){
	$error = "Quantity must be whole number";	
	include 'error.html.php';
    exit();
   }
   else if($_POST['sources'] != $_SESSION['branch']){
       	$error = "You can only move goods from this branch";	
	include 'error.html.php';
    exit();
       
   }else{
   
try
{
$pdo->beginTransaction();
//⋮ perform a series of queries
       $sql = 'INSERT INTO movement SET
        productid = :productid,
        userid = :userid,
        brandid = :brandid,
        categoryid = :categoryid,
        sourceid = :sourceid,
        destinationid = :destinationid,
        movedate = CURDATE(),
        saleid=:saleid,
        quantity = :quantity';
    $s = $pdo->prepare($sql);
    $s->bindValue(':productid',$_POST['id']);
    $s->bindValue(':userid', $_POST['users']);
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->bindValue(':brandid', $_POST['brands']);
    $s->bindValue(':categoryid',$_POST['categories']);
    $s->bindValue(':sourceid', $_POST['sources']);
    $s->bindValue(':saleid', $_SESSION['saleid']);
    $s->bindValue(':destinationid', $_POST['destinations']);
    $s->execute();
    //update product to reflect reduction quantity by move quantity
    $sql = 'UPDATE product SET quantity=quantity-:quantity WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':quantity',$_POST['quantity']);
    $s->execute();
    //update product table to add to destination branch
    $sql = 'UPDATE product SET quantity=quantity+:quantity WHERE branchid = :branchid AND name=:name
        AND brandid =:brandid AND categoryid =:categoryid';
    $s = $pdo->prepare($sql);
    $s->bindValue(':branchid', $_POST['destinations']);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':brandid', $_POST['brands']);
    $s->bindValue(':categoryid', $_POST['categories']);
    $s->bindValue(':quantity',$_POST['quantity']);
    $s->execute();
    //header('Location: .');
    $message = 'Moved successfully, Continue moving or ';
    $link = '.';
    include 'success.html.php';
    //echo $message;
    $pdo->commit();
    //exit();
    }
    catch (PDOException $e)
    {
    $pdo->rollBack();
    $error = 'Error performing the transaction of selling item'.$e;
    include 'error.html.php';
    //$GLOBALS['traker'] =1;
    exit();
    }
    }
}
}

//store product id for used bellow
if (!isset($_SESSION['cart']))
   {
      $_SESSION['cart'] = array();
    }
   

      // show sales
 if (isset($_GET['sales'])){
     try
{
    
  $sql = 'SELECT sales.id as sid, sales.customerid as customerid, product.name as pname,product.id as pid, sales.quantity as quantity, sales.price as unitprice, saledate, users.name as username, branch.name as branchname, category.name as categoryname, brand.name as brandname from sales inner join users on users.id=sales.userid inner join branch
      on branch.id=sales.sourceid inner join category on category.id=sales.categoryid inner join brand on brand.id=sales.brandid inner join product on product.id = sales.productid where sales.sourceid = :branchid and saledate = CURDATE() and credit=0 ';
 
    $s = $pdo->prepare($sql);
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        
    foreach ($s as $row)
{
  $sales[] = array('sid' => $row['sid'], 'pid' => $row['pid'], 'pname' => $row['pname'], 'quantity' => $row['quantity'], 'unitprice' => $row['unitprice'], 'saledate' => $row['saledate'], 'username' => $row['username'], 'branchname' => $row['branchname'], 'categoryname' => $row['categoryname'], 'brandname' => $row['brandname'], 'customerid' => $row['customerid']);
} 


}else {echo 'no record found';}

$thetitle = "Sales Record for Today";  
include 'sales.html.php';
exit();
}
catch (PDOException $e)
{
  $error = 'Error fetching sales from the database!'.$e;
  include 'error.html.php';
  exit();
}

}

if (isset($_GET['cart'])){
    
    try
{
      include  '../../includes/db.inc.php';

  $sql = 'SELECT sales.id as sid, brand.name as brandname, product.name as productname, category.name as categoryname, users.phone as phonenumber,
      branch.name as branchname, sales.quantity as quantity, sales.price as unitprice, sales.quantity*sales.price
      as total, product.id as pid, sales.customerid as receiptno, sales.saledate as date FROM sales inner join brand on brand.id = sales.brandid inner join product on product.id = sales.productid
      inner join category on category.id = sales.categoryid inner join users on users.id=sales.userid inner join branch on branch.id = sales.sourceid where sales.saleid =:saleid';
 
    $s = $pdo->prepare($sql);
    $s->bindValue(':saleid', $_SESSION['saleid']);
    //$s->bindValue(':productid',$_POST['id']);
    $s->execute();
}
catch (PDOException $e)
{
  $error = 'Error fetching sales from the database!'.$e;
  include 'error.html.php';
  exit();
}

foreach ($s as $row)
{
  $sales[] = array('pid' => $row['pid'],'sid' => $row['sid'],'brandname' => $row['brandname'], 'productname' => $row['productname'], 'categoryname' => $row['categoryname'], 'branchname' => $row['branchname'], 'unitprice' => $row['unitprice'],'quantity' => $row['quantity'], 'total' => $row['total'], 'receiptno' => $row['receiptno'],'date' => $row['date'], 'phonenumber' => $row['phonenumber']);
} 
     $cart = array();
     $total = 0;
           //$subtotal=0; 
         foreach ($_SESSION['cart'] as $id)
          {
           foreach ($sales as $product)
           {
              if ($product['pid'] == $id)
           {
              $cart[] = $product;
              $total += $product['total'];
             break;
           }
         }
       } 
   include 'invoice.html.php';
   exit();
   } 
  
  if (isset($_GET['Finished'])){
      // Display product list
include  '../../includes/db.inc.php';

try
{
  $sql = 'SELECT product.id as pid, product.name as pname, quantity, price, productdate, users.name as username, branch.name as branchname, category.name as categoryname, brand.name as brandname from product inner join users on users.id=userid inner join branch
      on branch.id=product.branchid inner join category on category.id=categoryid inner join brand on brand.id=brandid where product.branchid = :branchid and quantity <=0 order by product.name ';
 
    $s = $pdo->prepare($sql);
    $s->bindValue(':branchid', $_SESSION['branch']);
    $s->execute();
   
}
catch (PDOException $e)
{
  $error = 'Error fetching products from the database!'.$e;
  include 'error.html.php';
  exit();
}

foreach ($s as $row)
{
  $products[] = array('pid' => $row['pid'], 'pname' => $row['pname'], 'quantity' => $row['quantity'], 'price' => $row['price'], 'productdate' => $row['productdate'],'username' => $row['username'], 'branchname' => $row['branchname'], 'categoryname' => $row['categoryname'], 'brandname' => $row['brandname']);
} 
include 'finished.html.php';
exit();
  }         
if (isset($_POST['action']) and $_POST['action'] == 'Start')
{
session_regenerate_id();
}
 
// Display product list
include  '../../includes/db.inc.php';

try
{
  $sql = 'SELECT product.id as pid, product.name as pname, quantity, price, productdate, users.name as username, branch.name as branchname, category.name as categoryname, brand.name as brandname from product inner join users on users.id=userid inner join branch
      on branch.id=product.branchid inner join category on category.id=categoryid inner join brand on brand.id=brandid where product.branchid = :branchid order by product.name';
 
    $s = $pdo->prepare($sql);
    $s->bindValue(':branchid', $_SESSION['branch']);
    $s->execute();
   
}
catch (PDOException $e)
{
  $error = 'Error fetching products from the database!'.$e;
  include 'error.html.php';
  exit();
}

foreach ($s as $row)
{
  $products[] = array('pid' => $row['pid'], 'pname' => $row['pname'], 'quantity' => $row['quantity'], 'price' => $row['price'], 'productdate' => $row['productdate'],'username' => $row['username'], 'branchname' => $row['branchname'], 'categoryname' => $row['categoryname'], 'brandname' => $row['brandname']);
} 

try{
    include  '../../includes/db.inc.php';
    
    $sql= 'Select sum( price*quantity) as total, price, quantity from sales where saledate=CURDATE() and sourceid=:sourceid and credit=0';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':date', 2017-12-03);
    $s->bindValue(':sourceid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $todaySales = $row['total'];
        } 
    } 
    
}catch(PDOException $e){
    $error = "Unable to fetch today sales total".$e;
    include 'error.html.php';
}
/**
try{
    include  '../../includes/db.inc.php';
    
    $sql= 'Select sum( price*quantity) as total, price, quantity from sales where saledate=distinct(saledate) and sourceid=:sourceid';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':date', 2017-12-03);
    $s->bindValue(':sourceid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $allSales = $row['total'];
            echo $allSales;
        } 
    } 
    
}catch(PDOException $e){
    $error = "Unable to fetch today sales total".$e;
    include 'error.html.php';
}
*/
try{
    include  '../../includes/db.inc.php';
    
    $sql= 'Select count(id) as productcount from product  where branchid=:branchid';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':date', 2017-12-03);
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $productcount = $row['productcount'];
        } 
    } 
    
}catch(PDOException $e){
    $error = "Unable to fetch count".$e;
    include 'error.html.php';
}

try{
    include  '../../includes/db.inc.php';
    
    $sql= 'Select count(id) as finishcount from product  where quantity <=0 and branchid=:branchid';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':date', 2017-12-03);
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $finishcount = $row['finishcount'];
        } 
    } 
    
}catch(PDOException $e){
    $error = "Unable to fetch count".$e;
    include 'error.html.php';
}
 include 'products.html.php';