

<?php include_once '../../includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Administrative Area</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="../../css/reset.css" type="text/css" />
        <link rel="stylesheet" href="../../css/style.css" type="text/css" />
         <link rel="icon" href="../../images/favicon.ico">

    <title>Manage Products</title>

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link href="../../css/bootstrap.min.css" rel="stylesheet />
    
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
                             <li><a  class="" href="?add">Add New</a></li>
                             <li><a  class="" href="?deleteAll">Delete All</a></li>
                             <li><a  class="" href="?restoreAll">Restore All </a></li>
                             <li><?php include '../logout.inc.html.php'; ?></li>
                             
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
           
            <div class="container">
   
                <h5>Couldn't find product brand,product category or branch in add/edit form press the preference button below to add .</h5>
                <div class="pull-left">
        
<h5><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
Click to Add Your Preference
</button></h5>
<h5><?php htmlout(' Product Count: '.$productcount); ?> | Count of <a href='?Finished'>Finished Products: </a><?php htmlout ($finishcount); 
    $sn_count = 1;?></h5></div>
    
     <div class="pull-right">
                <h6>Use this form to see total amount in a day, month and year</h6>
                  <form action="" method="post" >
                <h5>Enter Date: <input type="text"  class ="custominput" name="date" placeholder="YYYY-MM-DD" >
                <input class="btn btn-md btn-primary " type="submit" name="action"   value="Get Total">
                </h5>
                </form>
            </div>
      <div class="row">
        <div class="col-md-6 mb-6">
               
        <!-- Button trigger modal -->
        
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
       <form action="" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enter your input</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
     
      <input type="text" name="preference" class="form-control" id="validationDefault01" required>
      </div>
      <div class="modal-footer">
        <input class="btn btn-sm btn-primary " type="submit" name="action" value="AddBrand">
        <input class="btn btn-sm btn-primary " type="submit" name="action" value="AddCategory">
        <input class="btn btn-sm btn-primary " type="submit" name="action" value="AddBranch">
            </div>
         </div>
        </form>
       </div>
       </div>
        </div>
  </div>
     
    <table class="table table-sm table-striped ">
    
  <thead>
    <tr>
      <th scope="col">S_No</th>
      <th scope="col">Product Name</th>
      <th scope="col">Quantity</th>
      <th scope="col">Price</th>
      <th scope="col">Branch</th>
      <th scope="col">User</th>
      <th scope="col">Date Entered</th>
      <th scope="col">Action </th>
    </tr>
  </thead>
  <tbody>
      <?php if ( $products == '' ){ echo '<tr><td>No Record Found</td></tr>';} else{ foreach ($products as $products): ?>
 
      <tr>
      <td><?php htmlout($sn_count); ?></td>
       <td id="name"><?php htmlout($products['pname'].' '.$products['brandname'].' '.$products['categoryname']); ?></td>
       <td><?php if($products['quantity'] <= 0){ htmlout('finished');}else{htmlout($products['quantity']);} ?></td>
        <td><?php htmlout($products['price']); ?></td>
         <td><?php htmlout($products['branchname']); ?></td>
         <td><?php htmlout($products['username']); ?></td>
         <td><?php htmlout($products['productdate']); ?></td>
     <form action="" method="post" >
      <td> <div class="listuser">
            
              <input type="hidden" name="id" value="<?php
                  echo $products['pid']; ?>">
              <input class="btn btn-sm btn-success" type="submit" name="action" value="Edit">
              <input class="btn btn-sm btn-primary" type="submit" name="action" value="Add Another Branch">
              <input class="btn btn-sm btn-secondary"  type="submit" name="action"   value="Record">
			  <input class="btn btn-sm btn-danger"  id="productDelete" type="submit" value="Delete">
            </div></td>
     </form>
    </tr>
  
      <?php $sn_count++;
      endforeach; } ?>
         </tbody>
       </table>
  </div><!-- Container -->
          
</div><!-- /#wrap -->
<script>
		//confirm Delete Button.
		function confirmDelete(event){
			var clickedButton = event.target;
			var nameElements = document.querySelectorAll("td#name");
			var product = nameElements[clickedButton.id].textContent;
			//alert();
			var c = confirm("Are you sure you want to delete "+ product );
			if(c == true)
				clickedButton.setAttribute("name", "action");
		}
		function deleteConfirm() {
			var inputElements = document.querySelectorAll("input#productDelete");
			var i = 0;
			var input, product;
							
			while ( i < inputElements.length ) {
				input = inputElements[i];
				input.setAttribute("id", i);
				input.addEventListener("click", confirmDelete, false);
				i += 1;
				//alert(input);
			}
		}
		deleteConfirm();
</script>
      <?php include '../../includes/footer.html.php'?>
        
     <script src="../../js/ie10-viewport-bug-workaround.js"></script>
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	 <script src="../../js/jquery-3.2.1.slim.min.js"></script>
	<script src="../../js/popover.js"></script>
	<script src="../../js/popper.min.js"></script>
	<script src="../../js/bootstrap.min.js"></script>
   
    </body>
</html>