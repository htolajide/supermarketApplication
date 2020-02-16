

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
   

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    
    <link href="../../css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../js/ie-emulation-modes-warning.js"></script>
      <script src="../../js/core.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	  <script language="JavaScript1.2"> 
            function printFunc() {
       var divToPrint = document.getElementById('recordprint');
       var htmlToPrint = '' +
        '<style type="text/css">' +
        'table {' +
        'width:90;' +
        '}' +
         'table thead, table tfoot td, table tr {' +
        'border:1px solid #000;' +
        'padding;0.2em;' +
        '}' +
        'table thead th  {' +
        'border-top:1px solid #000;' +
        'border-bottom:1px solid #000;' +
        'padding;0.2em;' +
        '}' +
        '.pull-right {' +
        ' float:right;' +
        '}' +
        '.pull-left {' +
        ' float:left;' +
        '}' +
        '#printarea{' +
        ' margin-top:1px;' +
        '}' +
        'H5 {' +
        ' margin-top:1px;' +
        '}' +
        'H4 {' +
        ' text-align:center;' +
        ' margin-top:1px;' +
        '}' +    
        '</style>';
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    //newWin.document.write("<h3 align='center'>Print Page</h3>");
    newWin.document.write(htmlToPrint);
    newWin.print();
    newWin.close();
    }
        </script>

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
                            <li><a  class="" href="?add">Add new Product</a></li>
                            <li> <a href='?'>Back To Control Panel</a></li>
                             <li><?php include '../logout.inc.html.php'; $sn_count=1; ?></li>
                             
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
     
    
        <div class="container">
      <div id="recordprint">
      <h4>Product Item Record with Date</h4>
      <div class="pull-left">
        <h5>Total Quantity Entered:<?php htmlout(" ".$nentered);   ?></h5>
        <h5>Total Quantity Received:<?php htmlout(" ".$totalreceived);   ?></h5>
        <h5>Overall Total Quantity of Product:<?php htmlout(" ".($nentered+$totalreceived));   ?></h5>
      </div>
      <div class="pull-right">
        <h5>Total Quantity Sold + Moved:<?php htmlout(" ".($nsold+$totalmoved));   ?></h5>
         <h5>Total Quantity Moved to Another Branch:<?php htmlout(" ".$totalmoved);   ?></h5>
        <h5>Total Number Remaining:<?php htmlout(" ".(($nentered+$totalreceived)-($nsold+$totalmoved)));   ?></h5>
     </div>
  
    <table class="table table-sm table-striped table-hover ">
         
  <thead>
    <tr>
      <th scope="col">S_No</th>
      <th scope="col">Brand</th>
      <th scope="col">Name</th>
      <th scope="col">Category</th>
      <th scope="col">Quantity</th>
      <th scope="col">Branch</th>
      <th scope="col">User</th>
      <th scope="col">Date Entered</th>
      
    </tr>
  </thead>
  <tbody>
      <?php if ( $products == '' ){ echo '<tr><td>No Record Found</td></tr>'; } else{ foreach ($products as $products): ?>
 
      <tr>
      <td><?php htmlout($sn_count); ?></td>
      <td><?php htmlout($products['brandname']); ?></td>
      <td><?php htmlout($products['pname']); ?></td>
      <td><?php htmlout($products['categoryname']); ?></td>
      <td><?php htmlout($products['quantity']); ?></td>
      <td><?php htmlout($products['branchname']); ?></td>
      <td><?php htmlout($products['username']); ?></td>
      <td><?php htmlout($products['date']); ?></td>
    
    </tr>
  
      <?php $sn_count++;
      endforeach; } ?>
         </tbody>
       </table>
	   <H4><a href=".">Back</a> | <input type='button' class='btn btn-primary' id="btn" value='Print' onclick='printFunc();'></H4>
	   </div>
  </div><!-- Container -->
          
</div><!-- /#wrap -->
      <?php include '../../includes/footer.html.php'?>
        
         <script language="JavaScript1.2">
   var deletebutton = document.getElementById("delete");
 deletebutton.addEventListener("click", function(event) {
confirm("Do you really want to delete this product");
}, false);

       
   </script>  
          
         <script src="../../js/ie10-viewport-bug-workaround.js"></script>
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    </body>
</html>