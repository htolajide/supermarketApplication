<?php
error_reporting( E_ALL );
// ini_set( "display_errors", 1 );
include_once '../../includes/magicquotes.inc.php';

require_once  '../../includes/access.inc.php';
if (!userIsLoggedIn())
{
  include '../login.html.php';
  exit();
}

if (!userHasRole('Account Administrator'))
{
  $error = 'Only Account Administrators may access this page.';
  include '../accessdenied.html.php';
  
  exit(); unset($_SESSION['loggedIn']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['name']);
}
try
  {
    include '../../includes/db.inc.php';
    $sql = 'SELECT name FROM users
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
				
if(isset($_GET['upload'])){
	upLoad();
	$message =  'Data Upload Successful';
    $link = '.';
    include 'success.html.php';
	exit();   
}

//add new user
if (isset($_GET['add']))
{
  include '../../includes/db.inc.php';
  

  $pageTitle = 'Add New User';
  $action = 'addform';
  $name = '';
  $email = '';
  $phone = '';
  $address = '';
  $gender = '';
  $date = '';
  
  $id = '';
  $button = 'Add User';

  // Build the list of roles
  try
  {
    $result = $pdo->query('SELECT id, description FROM role');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of roles.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $roles[] = array(
      'id' => $row['id'],
      'description' => $row['description'],
      'selected' => FALSE);
  }
// Build the list of branch
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
      'selected' => FALSE);
  }
  include 'form.html.php';
  exit();
}

if (isset($_GET['addform']))
{
  include '../../includes/db.inc.php';
  
  // user image/photo to register
	$img = $_POST['image'];
    $folderPath = "user_photos/";
  
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
  
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.png';
  
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);

  try
  {
    $sql = 'INSERT INTO users SET
        branchid = :branchid,
        name = :name,
        phone = :phone,
        address = :address,
        gender = :gender,
        userdate = CURDATE(),
        email = :email,
		photo = :photo';
    $s = $pdo->prepare($sql);
     $s->bindValue(':name', $_POST['name']);
     $s->bindValue(':branchid', $_POST['branches']);
    $s->bindValue(':phone', $_POST['phone']);
    $s->bindValue(':address', $_POST['address']);
    $s->bindValue(':gender', $_POST['gender']);
    $s->bindValue(':email', $_POST['email']);
	$s->bindValue(':photo', $file);
    $s->execute();
  }
  catch (PDOException $e)
  {
    //$error = 'Error adding user';
    $error=$e;
    include 'error.html.php';
    exit();
  }

  $userid = $pdo->lastInsertId();

  if ($_POST['password'] != '')
  {
    $password = md5($_POST['password'] . 'pos');

    try
    {
      $sql = 'UPDATE users SET
          password = :password
          WHERE id = :id';
      $s = $pdo->prepare($sql);
      $s->bindValue(':password', $password);
      $s->bindValue(':id', $userid);
      $s->execute();
    }
    catch (PDOException $e)
    {
      $error = 'Error setting users password.';
      include 'error.html.php';
      exit();
    }
  }

  if (isset($_POST['roles']))
  {
    foreach ($_POST['roles'] as $role)
    {
      try
      {
        $sql = 'INSERT INTO userrole SET
           userid = :userid,
            roleid = :roleid';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userid', $userid);
        $s->bindValue(':roleid', $role);
        $s->execute();
      }
      catch (PDOException $e)
      {
       // $error = 'Error assigning selected role to user.';
          $error=$e;
        include 'error.html.php';
        exit();
      }
    }
  }
 

  //header('Location: .');
  $message = 'user added';
  $link = '?add';
  include 'success.html.php';
  //exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'Edit')
{
  include  '../../includes/db.inc.php';

  try
  {
    $sql = 'SELECT id, branchid, name, phone, address, gender, userdate, photo, email FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching users details.'.$e;
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();

  $pageTitle = 'Edit Users';
  $action = 'editform';
  $name = $row['name'];
  $branchid = $row['branchid'];
  $phone = $row['phone'];
  $address = $row['address'];
  $gender = $row['gender'];
  $date = $row['userdate'];
  $email = $row['email'];
  $photo = $row['photo'];
  $id = $row['id'];
  $button = 'Submit';

  // Get list of roles assigned to this users
  try
  {
    $sql = 'SELECT roleid FROM userrole WHERE userid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $id);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of assigned roles.';
    include 'error.html.php';
    exit();
  }

  $selectedRoles = array();
  foreach ($s as $row)
  {
    $selectedRoles[] = $row['roleid'];
  }

  // Get list of branch assigned to this users
  try
  {
    $sql = 'SELECT branchid FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $id);
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
    $selectedBranch[] = $row['branchid'];
  }

  // Build the list of all roles
  try
  {
    $result = $pdo->query('SELECT id, description FROM role');
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching list of roles.';
    include 'error.html.php';
    exit();
  }

  foreach ($result as $row)
  {
    $roles[] = array(
      'id' => $row['id'],
      'description' => $row['description'],
      'selected' => in_array($row['id'], $selectedRoles));
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

  include 'form.html.php';
  exit();
}

if (isset($_GET['editform']))
{
  include  '../../includes/db.inc.php';

  try
  {
	  // check for student image/photo to register
	$img = $_POST['image'];
	//echo $img;
	$photopath = substr($img,0,7);
	//echo $photopath;
    $folderPath = "user_photos/";
	if ($photopath == $folderPath){
		$file = $img;
	}else{
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
  
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.png';
  
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);
	}
    $sql = 'UPDATE users SET
        name = :name,
        branchid = :branchid,
        phone = :phone,
        address = :address,
        gender = :gender,
        userdate = CURDATE(),
        email = :email,
		photo = :photo
        WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':branchid', $_POST['branches']);
    $s->bindValue(':phone', $_POST['phone']);
    $s->bindValue(':address', $_POST['address']);
    $s->bindValue(':gender', $_POST['gender']);
    $s->bindValue(':photo', $file);
    $s->bindValue(':email', $_POST['email']);
    $s->execute();
  }
  catch (PDOException $e)
  {
      $error = 'Error updating submitted user.'.$e;
      //$error= $e;
    include 'error.html.php';
    exit();
  }

  if ($_POST['password'] != '')
  {
    $password = md5($_POST['password'] . 'pos');

    try
    {
      $sql = 'UPDATE users SET
          password = :password
          WHERE id = :id';
      $s = $pdo->prepare($sql);
      $s->bindValue(':password', $password);
      $s->bindValue(':id', $_POST['id']);
      $s->execute();
    }
    catch (PDOException $e)
    {
      $error = 'Error setting author password.';
      include 'error.html.php';
      exit();
    }
  }

  try
  {
    $sql = 'DELETE FROM userrole WHERE userid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error removing obsolete user role entries.';
    include 'error.html.php';
    exit();
  }

  if (isset($_POST['roles']))
  {
    foreach ($_POST['roles'] as $role)
    {
      try
      {
        $sql = 'INSERT INTO userrole SET
            userid = :userid,
            roleid = :roleid';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userid', $_POST['id']);
        $s->bindValue(':roleid', $role);
        $s->execute();
      }
      catch (PDOException $e)
      {
        //$error = 'Error assigning selected role to users.';
        $error=$e;
        include 'error.html.php';
        exit();
      }
    }
  }
 
     //header('Location: .');
    $message = 'change successful';
    $link = '.';
    include 'success.html.php';
    //echo $message;
  //exit();
    
  
}

//dele all Users
 if (isset($_GET['deleteAll']))
{
  include '../../includes/db.inc.php';

  
  // Delete the author
  try
  {
    $sql = 'UPDATE users SET
          deleted = :deleted';
      $s = $pdo->prepare($sql);
      $s->bindValue(':deleted', 1);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting users.';
    include 'error.html.php';
    exit();
  }

  //header('Location: .');
  $message= 'Delete successful';
  $link='.';
  include 'success.html.php';
  exit();
}
  
  if (isset($_POST['action']) and $_POST['action'] == 'Delete')
{
  include '../../includes/db.inc.php';

  
  // Delete the user
  try
  {
    $sql = 'UPDATE users SET
          deleted = :deleted
          WHERE id = :id';
      $s = $pdo->prepare($sql);
      $s->bindValue(':deleted', 1);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting user.';
    include 'error.html.php';
    exit();
  }

  //header('Location: .');
  $message= 'delete successful';
  $link='.';
  include 'success.html.php';
  exit();
}


if (isset($_POST['action']) and $_POST['action'] == 'realDelete')
{
  include '../../includes/db.inc.php';

  // Delete role assignments for this author
  try
  {
    $sql = 'DELETE FROM userrole WHERE userid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error removing role the user.';
    include 'error.html.php';
    exit();
  }
    
 
  // Delete the author
  try
  {
    $sql = 'DELETE FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting author.';
    include 'error.html.php';
    exit();
  }

  //header('Location: .');
  $message= 'delete successful';
  $link='.';
  include 'success.html.php';
  //exit();
}

// Display user list
include  '../../includes/db.inc.php';

try
{
  $result = $pdo->query('SELECT users.id as uid, users.name as username, phone, photo, branch.name as branchname  FROM users inner join branch on branch.id = branchid where deleted = 0');
}
catch (PDOException $e)
{
  $error = 'Error fetching users from the database!'.$e;
  include 'error.html.php';
  exit();
}

foreach ($result as $row)
{
  $users[] = array('uid' => $row['uid'], 'username' => $row['username'],'phone' => $row['phone'],'branchname' => $row['branchname'], 'photo' => $row['photo'] );
}

include 'users.html.php';
