<?php
include_once '../../includes/magicquotes.inc.php';

require_once '../../includes/access.inc.php';

if (!userIsLoggedIn())
{
	include '../login.html.php'; 
	exit();
}
if (!userHasRole('Site Administrator'))
{
  $error = 'Only Site Administrators may access this page.';
  include '../accessdenied.html.php';
   //session_start();
        unset($_SESSION['loggedIn']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['name']);
        //include '../login.html.php'; 
  exit();
}


try
  {
    include '../../includes/db.inc.php';
    $sql = 'SELECT name, branchid FROM users
        WHERE email = :email AND password = :password';
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

                    $welcome = 'Hi';
					if (date("H") <= 12) {
						$welcome = 'Good Morning';
					} else if (date('H') > 12 && date("H") < 18) {
						$welcome = 'Good Afternoon';
					} else if(date('H') > 17) {
						$welcome = 'Good Evening';
					}
				$greeting = $welcome.', '.explode(" ",$row['name'])[0];
                $_SESSION['branch'] = $row['branchid'];


if (isset($_GET['add']))
{
  include '../../includes/db.inc.php';

  $pageTitle = 'Add New Product';
  $action = 'addform';
  $name = '';
  $quantity = '';
  $price = '';
  $brand = '';
  $user = '';
  $category ='';
  $productdate = '';
  $id = '';
  $button = 'Add Product';

  // Build the list of brand
  try
  {
    $result = $pdo->query('SELECT id, name FROM brand');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching brands.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $brands[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => FALSE);
  }
// Build the list of category
  try
  {
    $result = $pdo->query('SELECT id, name FROM category');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of category.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $categories[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => FALSE);
  }
  
  // Build the list of users
  try
  {
    $result = $pdo->query('SELECT id, name FROM users');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of category.'.$e;
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $users[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => FALSE);
  }
  
  // Build the list of branch
  try
  {
    $result = $pdo->query('SELECT id, name FROM branch');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of category.'.$e;
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $branches[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => FALSE);
  }
  include 'form.html.php';
  exit();
}

if (isset($_GET['addform']))
{
  include '../../includes/db.inc.php';
   $quantity = $_POST['quantity'];
    $price = $_POST['price'];
	$pattern = "/^\d+$/";
	//Input Validations
		
	if (!preg_match($pattern,$quantity)){
	$error = "Quantity must be whole number";	
	include 'error.html.php';
    exit();
   }
   else{

  try
  {
    $sql = 'INSERT INTO product SET
        name = :name,
        quantity = :quantity,
        price = :price,
        branchid = :branch,
        productdate = CURDATE(),
        brandid = :brand,
        categoryid = :category,
        userid= :user';
    $s = $pdo->prepare($sql);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->bindValue(':price', $_POST['price']);
    $s->bindValue(':brand', $_POST['brands']);
    $s->bindValue(':branch', $_POST['branches']);
    $s->bindValue(':category', $_POST['categories']);
    $s->bindValue(':user', $_POST['users']);
    $s->execute();
    
    $pid = $pdo->lastInsertId();
    
    $newsql = 'INSERT INTO newproduct SET
        quantity = :quantity,
        price = :price,
        branchid = :branch,
        productdate = CURDATE(),
        pid = :pid,
        categoryid = :category,
        userid= :user';
    $s = $pdo->prepare($newsql);
    
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->bindValue(':price', $_POST['price']);
    $s->bindValue(':pid', $pid);
    $s->bindValue(':branch', $_POST['branches']);
    $s->bindValue(':category', $_POST['categories']);
    $s->bindValue(':user', $_POST['users']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    //$error = 'Error adding user';
    $error=$e;
    include 'error.html.php';
    exit();
  }
   }
 //header('Location: .');
  $message = 'Product Added';
  $link = '?add';
  include 'success.html.php';
  //exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'Edit')
{
  include  '../../includes/db.inc.php';

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

  $pageTitle = 'Edit Product';
  $action = 'editform';
  $name = $row['name'];
  $quantity = $row['quantity'];
  $price = $row['price'];
  $brandid = $row['brandid'];
  $branchid = $row['branchid'];
  $categoryid = $row['categoryid'];
  $userid = $row['userid'];
  $productdate = $row['productdate'];
  $id = $row['id'];
  $button = 'Edit Product';
  
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

   // Get list of branch assigned to this product
  try
  {
    $sql = 'SELECT id FROM branch WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $branchid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedBranch = array();
  foreach ($s as $row)
  {
    $selectedBranch[] = $row['id'];
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

  // Build the list of all category
  try
  {
    $result = $pdo->query('SELECT id, name FROM category');
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
 // Build the list of all branch
  try
  {
    $result = $pdo->query('SELECT id, name FROM branch');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $branches[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedBranch));
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
    $result = $pdo->query('SELECT id, name FROM brand');
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


if (isset($_GET['editform']))
{
  include  '../../includes/db.inc.php';
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
	$pattern = "/^\d+$/";
	//Input Validations
		
	if (!preg_match($pattern,$quantity)){
	$error = "Quantity must be whole number";	
	include 'error.html.php';
    exit();
   }
   else{

  try
  {
    $sql = 'UPDATE product SET
        name = :name,
        price = :price,
        quantity = quantity+:quantity,
        brandid = :brandid,
        productdate = CURDATE(),
        userid = :userid,
        categoryid = :categoryid,
        branchid = :branchid
        WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':price', $_POST['price']);
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->bindValue(':brandid', $_POST['brands']);
    $s->bindValue(':userid', $_POST['users']);
    //$s->bindValue(':date', $_POST['userdate']);
    $s->bindValue(':categoryid', $_POST['categories']);
    $s->bindValue(':branchid', $_POST['branches']);
    $s->execute();
    
     $newsql = 'INSERT INTO newproduct SET
        quantity = :quantity,
        price = :price,
        branchid = :branch,
        productdate = CURDATE(),
        pid = :pid,
        categoryid = :category,
        userid= :user';
    $s = $pdo->prepare($newsql);
    
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->bindValue(':price', $_POST['price']);
    $s->bindValue(':pid', $_POST['id']);
    $s->bindValue(':branch', $_POST['branches']);
    $s->bindValue(':category', $_POST['categories']);
    $s->bindValue(':user', $_POST['users']);
    $s->execute();
  }
  catch (PDOException $e)
  {
      $error = 'Error updating submitted user.'.$e;
      //$error= $e;
    include 'error.html.php';
    exit();
  }
   }
   
  
     //header('Location: .');
    $message = 'Update Successful';
    $link = '.';
    $action = '';
    include 'success.html.php';
    //echo $message;
  //exit();
}

//Get the totals
if (isset($_POST['action']) and $_POST['action'] == 'Get Total'){
    try{
    include  '../../includes/db.inc.php';
    //get day total
    $sql= 'Select sum( price*quantity) as total, price, quantity from sales where saledate=:date and sourceid=:branchid and credit=0';
    $day = $pdo->prepare($sql);
    $day->bindValue(':date', $_POST['date']);
    $day->bindValue(':branchid', $_SESSION['branch']);
    if ($day->execute()){
        foreach ($day as $day){
            $dayTotal = $day['total'];
        } 
    } 
    
    $date = $_POST['date'];
    $month = explode("-",$date)[1];
    $year = explode("-",$date)[0];
    $d = explode("-",$date)[2];
    $sql= 'Select sum( price*quantity) as total, price, quantity from sales where Year(saledate)=:date and sourceid=:branchid and credit=0';
    $y = $pdo->prepare($sql);
    $y->bindValue(':date', $year);
    $y->bindValue(':branchid', $_SESSION['branch']);
    if ($y->execute()){
        foreach ($y as $y){
            $yearTotal = $y['total'];
        } 
    } 
    
    $sql= 'Select sum( price*quantity) as total, price, quantity from sales where Month(saledate)=:month and Year(saledate)=:year   and sourceid=:branchid and credit=0';
    $m = $pdo->prepare($sql);
    $m->bindValue(':month', $month);
    $m->bindValue(':year', $year);
    $m->bindValue(':branchid', $_SESSION['branch']);
    if ($m->execute()){
        foreach ($m as $m){
            $monthTotal = $m['total'];
        } 
    } 
    }catch(PDOException $e){
    $error = "Unable to fetch total".$e;
    include 'error.html.php';
    exit();
    }
    $type='button';$id='btn';$value='Print';$onclick='printFunc();';
    $message= 'Total for Day '.$d.' is &#8358;'.number_format($dayTotal,2).'| Total for Month '.$month.' is &#8358;'.number_format($monthTotal,2).'| Total for Year '.$year.' is &#8358;'.number_format($yearTotal,2).' <input type='.$type.' id='.$id.' value='.$value.' onclick='.$onclick.'>';
    $link='.';
    include 'success.html.php';
}
 //Product record
if (isset($_POST['action']) and $_POST['action'] == 'Record'){
    include  '../../includes/db.inc.php';

try
{
  $sql ='SELECT  product.name as pname, newproduct.quantity as quantity, newproduct.productdate as date, users.name as username, branch.name as branchname, category.name as categoryname, brand.name as brandname from newproduct inner join product on product.id=pid inner join users on users.id=newproduct.userid inner join branch on branch.id=newproduct.branchid inner join category on category.id=newproduct.categoryid inner join brand on brand.id=product.brandid where pid =:pid and product.deleted=0 order by pname';
    $s = $pdo->prepare($sql);
    $s->bindValue(':pid', $_POST['id']);
    if ($s->execute()){
        foreach ($s as $row)
{
  $products[] = array('pname' => $row['pname'], 'quantity' => $row['quantity'],  'date' => $row['date'],'username' => $row['username'], 'branchname' => $row['branchname'], 'categoryname' => $row['categoryname'], 'brandname' => $row['brandname']);
   
 //store product id for used bellow
 
}
  
    $sql= 'Select count(id) as timesold, sum(quantity) as nsold from sales where productid=:productid';
    $s = $pdo->prepare($sql);
    $s->bindValue(':productid',$_POST['id'] );
    //$s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $timesold = $row['timesold'];
            $nsold = $row['nsold'];
        } 
    } 
    
     $sql= 'Select quantity from product where id=:productid and deleted=0 ';
    $s = $pdo->prepare($sql);
    $s->bindValue(':productid',$_POST['id'] );
    //$s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $rnumber = $row['quantity'];
            
        } 
    } 
    
     $sql= 'Select sum(quantity) as nentered from newproduct where pid=:productid';
    $s = $pdo->prepare($sql);
    $s->bindValue(':productid',$_POST['id'] );
    //$s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $nentered = $row['nentered'];
            
        } 
    } 
    
     $sql= 'Select sum(quantity) as totalmoved from movement where productid =:productid and sourceid=:branchid';
    $s = $pdo->prepare($sql);
    $s->bindValue(':productid',$_POST['id'] );
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $totalmoved = $row['totalmoved'];
            
        } 
    } 
    
    $sql= 'Select sum(quantity) as totalreceived from movement where productid =:productid and destinationid=:branchid';
    $s = $pdo->prepare($sql);
    $s->bindValue(':productid',$_POST['id'] );
    $s->bindValue(':branchid', $_SESSION['branch']);
    if ($s->execute()){
        foreach ($s as $row){
            $totalreceived = $row['totalreceived'];
            
        } 
    } 
}
}
catch (PDOException $e)
{
  $error = 'Error fetching products from the database!'.$e;
  include 'error.html.php';
  exit();
}

include 'record.html.php';
exit();
} 

 // Restore all product
 if (isset($_GET['restoreAll']))
{
  include '../../includes/db.inc.php';
  try
  {
    $sql = 'UPDATE product SET
          deleted = :deleted';
      $s = $pdo->prepare($sql);
      $s->bindValue(':deleted', 0);
      $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error restoring products.';
    include 'error.html.php';
    exit();
  }

  //header('Location: .');
  $message= 'Restore successful';
  $link='.';
  include 'success.html.php';
  //exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'Add Another Branch')
{
  include  '../../includes/db.inc.php';

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

  $pageTitle = 'Add This Product for Another Branch';
  $action = 'addbranch';
  $name = $row['name'];
  $quantity = $row['quantity'];
  $price = $row['price'];
  $brandid = $row['brandid'];
  $branchid = $row['branchid'];
  $categoryid = $row['categoryid'];
  $userid = $row['userid'];
  $productdate = $row['productdate'];
  $id = $row['id'];
  $button = 'Add Product';
  
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

   // Get list of branch assigned to this product
  try
  {
    $sql = 'SELECT id FROM branch WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $branchid);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedBranch = array();
  foreach ($s as $row)
  {
    $selectedBranch[] = $row['id'];
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

  // Build the list of all category
  try
  {
    $result = $pdo->query('SELECT id, name FROM category');
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
 // Build the list of all branch
  try
  {
    $result = $pdo->query('SELECT id, name FROM branch');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $branches[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'selected' => in_array($row['id'], $selectedBranch));
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
    $result = $pdo->query('SELECT id, name FROM brand');
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

if (isset($_GET['addbranch']))
{
  include  '../../includes/db.inc.php';


   try
  {
     $sql = 'SELECT  branchid FROM product WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of branch.';
    include 'error.html.php';
    exit();
  } 
  foreach($s as $row){
    if (($_POST['branches'] == $row['branchid'])) {
           $message = 'This product is already existing in this branch';
           $link = '.';
           include 'success.html.php';
           exit;
    }else {
    try
  { 
    $sql = 'INSERT INTO product SET
        name = :name,
        price = :price,
        quantity = :quantity,
        brandid = :brandid,
        productdate = CURDATE(),
        userid = :userid,
        categoryid = :categoryid,
        branchid = :branchid';
       
    $s = $pdo->prepare($sql);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':price', $_POST['price']);
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->bindValue(':brandid', $_POST['brands']);
    $s->bindValue(':userid', $_POST['users']);
    //$s->bindValue(':date', $_POST['userdate']);
    $s->bindValue(':categoryid', $_POST['categories']);
    $s->bindValue(':branchid', $_POST['branches']);
    $s->execute();
    
    $pid = $pdo->lastInsertId();
    
    $newsql = 'INSERT INTO newproduct SET
        quantity = :quantity,
        price = :price,
        branchid = :branch,
        productdate = CURDATE(),
        pid = :pid,
        categoryid = :category,
        userid= :user';
    $s = $pdo->prepare($newsql);
    
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->bindValue(':price', $_POST['price']);
    $s->bindValue(':pid', $pid);
    $s->bindValue(':branch', $_POST['branches']);
    $s->bindValue(':category', $_POST['categories']);
    $s->bindValue(':user', $_POST['users']);
    $s->execute();
  }
  catch (PDOException $e)
  {
      $error = 'Error adding product.'.$e;
      //$error= $e;
    include 'error.html.php';
    exit();
    }
    
  }
   // header('Location: .');
    $message = 'Added successfully';
    $link = '.';
    include 'success.html.php';
    //echo $message;
    //exit();
  }
}

// Delete a specified product permanently
 if (isset($_POST['action']) and $_POST['action'] == 'Delete Permanently')
{
  include '../../includes/db.inc.php';
  try
  {
    $sql = 'DELETE FROM product WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
	
	$sql = 'DELETE FROM newproduct WHERE pid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting product.';
    include 'error.html.php';
    exit();
  }

  //header('Location: .');
  $message= 'Product Permanently deleted';
  $link='.';
  include 'success.html.php';
  //exit();
}

  // Delete a specified product
 if (isset($_POST['action']) and $_POST['action'] == 'Delete')
{
  include '../../includes/db.inc.php';
  try
  {
    $sql = 'UPDATE product SET
          deleted = :deleted
          WHERE id = :id';
      $s = $pdo->prepare($sql);
      $s->bindValue(':deleted', 1);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting product.';
    include 'error.html.php';
    exit();
  }

  //header('Location: .');
  $message= 'delete successful';
  $link='.';
  include 'success.html.php';
  //exit();
}

 // Delete all product
 if (isset($_GET['deleteAll']))
{
  include '../../includes/db.inc.php';
  try
  {
    $sql = 'UPDATE product SET
          deleted = :deleted';
      $s = $pdo->prepare($sql);
      $s->bindValue(':deleted', 1);
      $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting products.';
    include 'error.html.php';
    exit();
  }

  //header('Location: .');
  $message= 'Delete successful';
  $link='.';
  include 'success.html.php';
  //exit();
}

// add brand
if (isset($_POST['action']) and $_POST['action'] == 'AddBrand')
{
    $action = '';
    
  include '../../includes/db.inc.php';

  // Delete role assignments for this author
	$sql= 'Select name from brand';
    $s = $pdo->prepare($sql);
    
    if ($s->execute()){
        foreach ($s as $row){
            $name = $row['name'];
            
        } 
		if($name == $_POST['preference'] ){
			$error = 'Brand Already Exist.';
			include 'error.html.php';
			exit();
			
		}
    } 
  try
  {
    $sql = 'INSERT INTO brand set name=:name';
    $s = $pdo->prepare($sql);
    $s->bindValue(':name', $_POST['preference']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error adding brand.';
    include 'error.html.php';
    exit();
  }
   $message= 'Brand Addition successful';
  $link='.';
  include 'success.html.php';
  //exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'AddBranch')
{
    $action = '';
    
  include '../../includes/db.inc.php';
  
  $sql= 'Select name from branch';
    $s = $pdo->prepare($sql);
    
    if ($s->execute()){
        foreach ($s as $row){
            $name = $row['name'];
            
        } 
		if($name == $_POST['preference'] ){
			$error = 'Branch Already Exist.';
			include 'error.html.php';
			exit();
			
		}
    } 

  try
  {
    $sql = 'INSERT INTO branch set name=:name';
    $s = $pdo->prepare($sql);
    $s->bindValue(':name', $_POST['preference']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error adding branch.'.$e;
    include 'error.html.php';
    exit();
  }
  $message= 'Branch Addition successful';
  $link='.';
  include 'success.html.php';
  //exit();
}
if (isset($_POST['action']) and $_POST['action'] == 'AddCategory')
{ 
    $action = '';
  include '../../includes/db.inc.php';

  // add category
  $sql= 'Select name from category';
    $s = $pdo->prepare($sql);
    
    if ($s->execute()){
        foreach ($s as $row){
            $name = $row['name'];
            
        } 
		if($name == $_POST['preference'] ){
			$error = 'Category Already Exist.';
			include 'error.html.php';
			exit();
			
		}
    } 
  try
  {
    $sql = 'INSERT INTO category set name=:name';
    $s = $pdo->prepare($sql);
    $s->bindValue(':name', $_POST['preference']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error adding category.';
    include 'error.html.php';
    exit();
  }
  $message= 'Category Addition successful';
  $link='.';
  include 'success.html.php';
  //exit();
}
// display finished products

if (isset($_GET['Finished'])){
    include  '../../includes/db.inc.php';

try
{
  $result = $pdo->query('SELECT product.id as pid, product.name as pname, quantity, price, productdate, users.name as username, branch.name as branchname, category.name as categoryname, brand.name as brandname from product inner join users on users.id=userid inner join branch
      on branch.id=product.branchid inner join category on category.id=categoryid inner join brand on brand.id=brandid where product.branchid='.$branchid.' and quantity <=0 and Product.deleted = 0 order by product.name');
}
catch (PDOException $e)
{
  $error = 'Error fetching products from the database!'.$e;
  include 'error.html.php';
  exit();
}

foreach ($result as $row)
{
  $products[] = array('pid' => $row['pid'], 'pname' => $row['pname'], 'quantity' => $row['quantity'], 'price' => $row['price'], 'productdate' => $row['productdate'],'username' => $row['username'], 'branchname' => $row['branchname'], 'categoryname' => $row['categoryname'], 'brandname' => $row['brandname']);
   
 //store product id for used bellow
 
}
include 'finished.html.php';
 exit();   
}

// Display product list
include  '../../includes/db.inc.php';

try
{
  $result = $pdo->query('SELECT product.id as pid, product.name as pname, quantity, price, productdate, users.name as username, branch.name as branchname, category.name as categoryname, brand.name as brandname from product inner join users on users.id=userid inner join branch
      on branch.id=product.branchid inner join category on category.id=categoryid inner join brand on brand.id=brandid where product.branchid='.$_SESSION['branch'].' AND product.deleted=0 order by product.name');
}
catch (PDOException $e)
{
  $error = 'Error fetching products from the database!'.$e;
  include 'error.html.php';
  exit();
}

foreach ($result as $row)
{
  $products[] = array('pid' => $row['pid'], 'pname' => $row['pname'], 'quantity' => $row['quantity'], 'price' => $row['price'], 'productdate' => $row['productdate'],'username' => $row['username'], 'branchname' => $row['branchname'], 'categoryname' => $row['categoryname'], 'brandname' => $row['brandname']);
   
 //store product id for used bellow
 
}

try{
    include  '../../includes/db.inc.php';
    
    $sql= 'Select count(id) as productcount from product where branchid=:branchid and deleted=0';
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
    
    $sql= 'Select count(id) as finishcount from product  where branchid=:branchid and quantity <=0 and deleted=0 ';
    $s = $pdo->prepare($sql);
    //$s->bindValue(':date', 2017-12-03);
    $s->bindValue(':branchid',$_SESSION['branch']);
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
