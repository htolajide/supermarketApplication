<?php include_once '../includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Password Reset Page</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="../css/reset.css" type="text/css" />
        <link rel="stylesheet" href="../css/style.css" type="text/css" />
         <link rel="icon" href="../images/favicon.ico">

    

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    
    <link href="../css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>
    <body>
        <?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
        <div id="wrap">
            <header class="main-header">
                <div class="container">
                    <div class="logo">
                       <script language="JavaScript1.2">                   
  
var myimages=new Array();
var delay = 500;
var number;

  myimages[1]="../images/verologo00.png";
  myimages[2]="../images/verologo01.png";
  myimages[3]="../images/verologo02.png";
 
  number=1;
     
  function animate(){
      
   
   for(i=1; i<3; i++){ document.image_rotate.src = myimages[number];
     number++;
     if (number > 3) number=1;
   }
    
}

</script>
     
          
                
             <img name="image_rotate" src="../images/verologo00.png" onLoad="setTimeout('animate()', delay);" alt="verologo" />
             
                    </div><!-- /.logo -->
                    <nav class="main-nav">
                        <ul>
                             <li><a href="../">Return to Home Page</a></li>
                             
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
        <div class="container"> 
        <form method="post" class="form-signin">
        <h2 class="form-signin-heading btn-lg btn-secondary  btn-block">Password Reset Area</h2>
		<?php if (isset($_POST['submit'])){
			$email = $_POST['email'];
			$password = MD5($_POST['password'].'pos');
			$password2 = MD5($_POST['password2'].'pos');
			if($password != $password2){?>
				<p><?php echo'<div class="alert alert-warning alert-dismissible fade show" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            Password do not match. You should check in on some of those password fields below.
           </div>' ?></p>
			<?php }else{
				
			 include  '../includes/db.inc.php';
  try
  {
	//check if email is registered
	$sql = 'SELECT COUNT(*) as count from  users
        WHERE email = :email AND deleted = 0';
    $s1 = $pdo->prepare($sql);
    $s1->bindValue(':email', $email);
    //$s->bindValue(':password', $password);
    $s1->execute();
	$s = false;
	$row = $s1->fetch();
		if($row) {
			if($row['count'] > 0) {
		
	$sql = 'UPDATE users SET
        password = :password
        WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $email);
	$s->bindValue(':password', $password);
    $s->execute();
			}else{
				?>
				<p><?php echo'<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            Email not registered. You should check the email entered below.
			</div>' ?></p>
			<?php
			}
		}
  }
  catch (PDOException $e)
  {
    //$error = 'Error updating submitted user.';
      //$error= $e;
    if(isset($e)){
	?>
				<p><?php echo'<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            Your request failed. You should check the entered email.
			</div>';} ?></p>
				
			<?php
				
			}?>  	
			<p><?php if($s){
				echo'<div class="alert alert-success alert-dismissible fade show" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            Password reset successful. You should keep the new password save.
			</div>'; }?></p><?php
		}
	}
		?>
        <label for="inputEmail" > Your Email</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" >New Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
		<label for="reenterPassword" >Confirm Password</label>
        <input type="password" name="password2" id="password" class="form-control" placeholder="Password" required>
        <input type="hidden" name="action" value="login">
        <input class="btn btn-lg btn-secondary center-block" type="submit" name="submit" value="Submit"/>
        <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#"></a>
         
      </form>

               
              
             
            </div><!-- /.container-->                
        </div><!-- /#wrap -->
      <?php include '../includes/footer.html.php'?>
        
         <script src="../js/ie10-viewport-bug-workaround.js"></script>
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    </body>
</html>