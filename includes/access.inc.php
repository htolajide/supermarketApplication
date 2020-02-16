<?php

function userIsLoggedIn()
{
  if (isset($_POST['action']) and $_POST['action'] == 'login')
  {
    if (!isset($_POST['email']) or $_POST['email'] == '' or
      !isset($_POST['password']) or $_POST['password'] == '')
    {
      $GLOBALS['loginError'] = 'Please fill in both fields';
      return FALSE;
    }

    $password = md5($_POST['password'].'pos');

    if (databaseContainsAuthor($_POST['email'], $password))
    {
      session_start();
      $_SESSION['loggedIn'] = TRUE;
      $_SESSION['email'] = $_POST['email'];
      $_SESSION['password'] = $password;
	  attemptLogin($_POST['email'], $_POST['password'], true);
	  upLoad();
      return TRUE;
    }
    else
    {
      session_start();
      unset($_SESSION['loggedIn']);
      unset($_SESSION['email']);
      unset($_SESSION['password']);
      $GLOBALS['loginError'] =
          'The specified email address or password was incorrect.';
	  attemptLogin($_POST['email'], $_POST['password'], false);
      return FALSE;
    }
  }

  if (isset($_POST['action']) and $_POST['action'] == 'logout')
  {
    session_start();
    unset($_SESSION['loggedIn']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    unset($_SESSION['name']);
    backUp();
    header('Location: ' . $_POST['goto']);
    exit();
  }

  session_start();
  if (isset($_SESSION['loggedIn']))
  {
    return databaseContainsAuthor($_SESSION['email'], $_SESSION['password']);
  }
}

function attemptLogin($email, $password, $succeeded)
{
  include 'db.inc.php';

  try
  {
    $sql = 'INSERT INTO attempted_logins SET email=:email, password=:password, succeeded=:succeeded, created_at = NOW()';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $email);
    $s->bindValue(':password', $password);
	$s->bindValue(':succeeded', $succeeded);
    $s->execute();
  }
  catch (PDOException $e)
  {
      //$GLOBALS['loginError'] = 'Error searching for user.';
     echo $e;
  }
}

function upLoad(){
	include '../../includes/db.inc.php';
	//create csv file for backup.
	//save all file as an array.
	$files = array("'C:/BackUpDatabase/sales.csv'","'C:/BackUpDatabase/product.csv'","'C:/BackUpDatabase/newproduct.csv'",
	"'C:/BackUpDatabase/branch.csv'","'C:/BackUpDatabase/brand.csv'","'C:/BackUpDatabase/category.csv'",
	"'C:/BackUpDatabase/movement.csv'","'C:/BackUpDatabase/transactions.csv'", "'C:/BackUpDatabase/users.csv'",
	"'C:/BackUpDatabase/role.csv'","'C:/BackUpDatabase/userrole.csv'","'C:/BackUpDatabase/customer.csv'","'C:/BackUpDatabase/attempted_logins.csv'");
	$unlink = array('C:/BackUpDatabase/sales.csv','C:/BackUpDatabase/product.csv','C:/BackUpDatabase/newproduct.csv',
	'C:/BackUpDatabase/branch.csv','C:/BackUpDatabase/brand.csv','C:/BackUpDatabase/category.csv',
	'C:/BackUpDatabase/movement.csv','C:/BackUpDatabase/transactions.csv', 'C:/BackUpDatabase/users.csv',
	'C:/BackUpDatabase/role.csv','C:/BackUpDatabase/userrole.csv','C:/BackUpDatabase/customer.csv','C:/BackUpDatabase/attempted_logins.csv');
	$tables = array('sales','product','newproduct','branch','brand','category','movement','transactions','users','role','userrole', 'customer','attempted_logins');

	for($i=0; $i<count($files); $i++){
		if (!$file = @ fopen($files[$i], 'x')) {
		// write conten to remote server
			try{
	
				$uploadsql = 'LOAD DATA INFILE '.$files[$i].' INTO TABLE '.$tables[$i].' FIELDS TERMINATED BY \',\' LINES TERMINATED BY \'\n\'';
				$deletesql = 'DELETE FROM '.$tables[$i].'';
				$delete = $pdo->prepare($deletesql);
				$delete->execute();
				$upload = $pdo->prepare($uploadsql);
				$upload->execute();
			}catch(exception $e){
				$error = 'Unable to connect to the database server.'.$e;
				echo $error;
			}
		}
	}
}
	
function backUp(){
	include '../../includes/db.inc.php';
	//create csv file for backup.
	//save all file as an array.
	$files = array("'C:/BackUpDatabase/sales.csv'","'C:/BackUpDatabase/product.csv'","'C:/BackUpDatabase/newproduct.csv'",
	"'C:/BackUpDatabase/branch.csv'","'C:/BackUpDatabase/brand.csv'","'C:/BackUpDatabase/category.csv'",
	"'C:/BackUpDatabase/movement.csv'","'C:/BackUpDatabase/transactions.csv'", "'C:/BackUpDatabase/users.csv'",
	"'C:/BackUpDatabase/role.csv'","'C:/BackUpDatabase/userrole.csv'","'C:/BackUpDatabase/customer.csv'","'C:/BackUpDatabase/attempted_logins.csv'");
	$unlink = array('C:/BackUpDatabase/sales.csv','C:/BackUpDatabase/product.csv','C:/BackUpDatabase/newproduct.csv',
	'C:/BackUpDatabase/branch.csv','C:/BackUpDatabase/brand.csv','C:/BackUpDatabase/category.csv',
	'C:/BackUpDatabase/movement.csv','C:/BackUpDatabase/transactions.csv', 'C:/BackUpDatabase/users.csv',
	'C:/BackUpDatabase/role.csv','C:/BackUpDatabase/userrole.csv','C:/BackUpDatabase/customer.csv','C:/BackUpDatabase/attempted_logins.csv');
	$tables = array('sales','product','newproduct','branch','brand','category','movement','transactions','users','role','userrole', 'customer','attempted_logins');

	 for($i=0; $i<count($files); $i++){
	if (!$file = @ fopen($files[$i], 'x')) {
	// write the contents
	
	 unlink($unlink[$i]);
		try{
		
	$sql = 'SELECT * INTO OUTFILE '.$files[$i].' FIELDS TERMINATED BY \',\' LINES TERMINATED BY \'\n\' FROM '.$tables[$i].'';
	$s = $pdo->prepare($sql);
    $s->execute();

	}catch(exception $e){
		$error = 'Server Cannot Output File'.$e;
		echo $errror;
	}	
	}
  }
}


function databaseContainsAuthor($email, $password)
{
  include 'db.inc.php';

  try
  {
    $sql = 'SELECT COUNT(*) as count, name FROM users
        WHERE email = :email AND password = :password AND deleted = 0';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $email);
    $s->bindValue(':password', $password);
    $s->execute();
  }
  catch (PDOException $e)
  {
      $GLOBALS['loginError'] = 'Error searching for user.';
    //include 'error.html.php';
    exit();
  }

  $row = $s->fetch();
  
  if ($row['count'] > 0)
  {
  
    return TRUE;
  }
  else
  {
    return FALSE;
  } 
  
}

function userHasRole($role)
{
  include 'db.inc.php';

  try
  {
    $sql = "SELECT COUNT(*) FROM users
        INNER JOIN userrole ON users.id = userid
        INNER JOIN role ON roleid = role.id
        WHERE email = :email AND role.id = :roleId";
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_SESSION['email']);
    $s->bindValue(':roleId', $role);
    $s->execute();
  }
  catch (PDOException $e)
  {
     $GLOBALS['loginError'] = 'Error searching for user roles.';
    //include 'error.html.php';
    exit();
  }

  $row = $s->fetch();

  if ($row[0] > 0)
  {
    return TRUE;
  }
  else
  {
    return FALSE;
  }
}
