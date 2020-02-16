
<?php include_once '../../includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage Users</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="../../css/reset.css" type="text/css" />
        <link rel="stylesheet" href="../../css/style.css" type="text/css" />
         <link rel="icon" href="../../images/favicon.ico">

 

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    
    <link href="../../css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../js/ie-emulation-modes-warning.js"></script>

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

  myimages[1]="../../images/verologo00.png";
  myimages[2]="../../images/verologo01.png";
  myimages[3]="../../images/verologo02.png";
 
  number=1;
     
  function animate(){
      
   
   for(i=1; i<3; i++){ document.image_rotate.src = myimages[number];
     number++;
     if (number > 3) number=1;
   }
    
}

</script>
     
          
                
             <img name="image_rotate" src="../../images/verologo00.png" onLoad="setTimeout('animate()', delay);" alt="verologo" />
             
                    </div><!-- /.logo -->
                     <span class="greeting"><?php echo $greeting; ?></span>
                    <nav class="main-nav">
                        <ul>
							 <li><a href="../">Home</a></li>
                             <li> <a class="active" href="." >Control</a></li>
                             <li><a class="" href="?add">Add new User</a></li>
							 <li><a class="" href="?upload">Upload</a></li>
                             <li><?php include '../logout.inc.html.php'; ?></li>
                             
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
            <div class="container">
               
    <h4>List of users.</h4>
            
    <table class="table table-sm table-striped ">
  <thead>
    <tr>
	  <th scope="col">Photo</th>
      <th scope="col">Name</th>
      <th scope="col">Phone No</th>
      <th scope="col">Branch</th>
      <th scope="col">Action </th>
    </tr>
  </thead>
  <tbody>
      <?php if ( $users == '' ){ echo '<tr><td>No Record Found</td></tr>'; } else{ foreach ($users as $users): ?>
      
    
       <form action="" method="post" >
    <tr>
	 <td id="photos"><img src="<?php htmlout($users['photo']); ?>" alt="..." class="profile-photo"></td>
      <td id="name"><?php htmlout($users['username']); ?></td>
      <td><?php htmlout($users['phone']); ?></td>
      <td><?php htmlout($users['branchname']); ?></td>
      <td> <div class="listuser">
             
              <input type="hidden" name="id" value="<?php
                  echo $users['uid']; ?>">
              <input class="btn btn-sm btn-secondary " type="submit" name="action" value="Edit">
              <input class="btn btn-sm btn-danger" id="userdelete" type="submit" value="Delete">
            </div></td>
     
    
    </tr>
    </form>
      <?php endforeach; } ?>
  </tbody>
</table>
<div class="big-image"></div>
 </div><!-- Container -->              
        </div><!-- /#wrap -->
<script>
	function init(){
		console.log("Hello from javascript");
		var lightboxElements = "<div id='lightbox'>";
		lightboxElements    += "<div id='overlay' class='hidden'></div>";
		lightboxElements    += "<img class='hidden' id='big-image' />";
		lightboxElements    += "</div>";
		document.querySelector(".big-image").innerHTML += lightboxElements; 
		var bigImage = document.querySelector("#big-image")
		bigImage.addEventListener("click",toggle, false);
		profilephoto();
		deleteConfirm();
	}

	function toggle( event ){
		var clickedImage = event.target;
		var bigImage = document.querySelector("#big-image");
		var overlay = document.querySelector("#overlay");
		bigImage.src = clickedImage.src;
		if ( overlay.getAttribute("class") === "hidden" ) {
			overlay.setAttribute("class", "showing");
			bigImage.setAttribute("class", "showing");
		} else {
			overlay.setAttribute("class", "hidden");
			bigImage.setAttribute("class", "hidden");
		}
	}

	function profilephoto() {
		var tdElements = document.querySelectorAll("td#photos");
		var i = 0;
		var image, td;
		
		while ( i < tdElements.length ) {
			td = tdElements[i];
			//li.setAttribute("class", "lightbox");
			image = td.querySelector("img");
			image.addEventListener("click", toggle, false);
			i += 1;
		}
    }
	//confirm Delete Button.
	function confirmDelete(event){
		var clickedButton = event.target;
		var nameElements = document.querySelectorAll("td#name");
		var user = nameElements[clickedButton.id].textContent;
		//alert();
		var c = confirm("Are you sure you want to delete "+user );
		if(c == true)
			clickedButton.setAttribute("name", "action");
		
    
	}
	function deleteConfirm() {
		var inputElements = document.querySelectorAll("input#userdelete");
		var i = 0;
		var input, user;
							
		while ( i < inputElements.length ) {
			input = inputElements[i];
			input.setAttribute("id", i);
			input.addEventListener("click", confirmDelete, false);
			i += 1;
			//alert(input);
		}
	}
	init();
		</script>
      <?php include '../../includes/footer.html.php'?>
         <script src="../../js/ie10-viewport-bug-workaround.js"></script>
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  
    </body>
</html>