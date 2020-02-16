<?php include_once '../../includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage sales</title>
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
                        <ul>  <li> <a href="?sales" >Sales Record</a></li>
                             <li><?php include '../logout.inc.html.php'; ?></li>
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
           
            <div class="container">
                 <h4>Sales Record for: <?php htmlout($_POST['date']); ?></h4>
				<div class="alert alert-info" >
					 <h5>NOTE: Use the item code to find an item to undo/cancel</h5>
					 <h5>Count of Items:<?php htmlout(" ".$itemcount);   ?></h5>
					 <h5>Total sales: &#8358;<?php htmlout(number_format($daytotal,2)); ?></h5>
				</div>
                <table class="table table-sm table-striped table-hover ">
  <thead>
    <tr>
      <th scope="col">S_No</th>
      <th scope="col">Brand</th>
      <th scope="col">Name</th>
      <th scope="col">Category</th>
      <th scope="col">Quantity</th>
      <th scope="col">Price</th>
      <th scope="col">Branch</th>
      <th scope="col">User</th>
      <th scope="col">ItemCode</th>
      <th scope="col">Date Entered</th>
      <th scope="col">Action <a href="?">Back</a> </th>
    </tr>
  </thead>
  <tbody>
      <?php $sn_count = 1;
      if ( $sales == '' ){ echo '<tr><td>No Record Found</td></tr>';} else{
      foreach ($sales as $sales): ?>
 
      <tr>
      <td><?php htmlout($sn_count); ?></td>
      <td><?php htmlout($sales['brandname']); ?></td>
      <td id="name"><?php htmlout($sales['pname']); ?></td>
      <td><?php htmlout($sales['categoryname']); ?></td>
       <td><?php htmlout($sales['quantity']); ?></td>
        <td><?php htmlout($sales['unitprice']); ?></td>
         <td><?php htmlout($sales['branchname']); ?></td>
         <td><?php htmlout($sales['username']); ?></td>
          <td>#<?php htmlout($sales['customerid']); ?></td>
          <td><?php htmlout($sales['saledate']); ?></td>
     
      <td> <div class="listuser">
              <form action="" method="post" >
              <input type="hidden" name="sid" value="<?php
                  echo $sales['sid']; ?>">
              <input type="hidden" name="pid" value="<?php
                  echo $sales['pid']; ?>">
              <input type="hidden" name="quantity" value="<?php
                  echo $sales['quantity']; ?>">
			  <input type="hidden" name="date" value="<?php
                  echo $_POST['date']; ?>">
              <input class="btn btn-sm btn-primary" type="submit" name="action"   value="Quantity Profile">
              <input class="btn btn-sm btn-danger" id="undoProduct" type="submit" value="Undo Sale">
             </form>
              </div></td>
     
    </tr>
  
      <?php $sn_count++;
      endforeach;} ?>
         </tbody>
       </table>
  </div><!-- Container -->
          
</div><!-- /#wrap -->
<script>
		//confirm Delete Button.
		function confirmUndo(event){
			var clickedButton = event.target;
			var nameElements = document.querySelectorAll("td#name");
			var product = nameElements[clickedButton.id].textContent;
			//alert();
			var c = confirm("Are you sure you want to undo "+ product );
			if(c == true)
				clickedButton.setAttribute("name", "action");
		}
		function undoConfirm() {
			var inputElements = document.querySelectorAll("input#undoProduct");
			var i = 0;
			var input, product;
							
			while ( i < inputElements.length ) {
				input = inputElements[i];
				input.setAttribute("id", i);
				input.addEventListener("click", confirmUndo, false);
				i += 1;
				//alert(input);
			}
		}
		undoConfirm();
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