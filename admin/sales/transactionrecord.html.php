<?php include_once '../../includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Transaction Page</title>
        <meta charset="utf-8" />
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
                    <nav class="main-nav">
                        <ul>
                            <li><a class="active" href="."><?php echo $pagetitle ?></a></li>
                             <li><a href=".">Back To Control Panel</a></li>
                              <li><?php include '../logout.inc.html.php'; ?></li>
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
            <div class="container">                
             <h4><?php echo $title.' '.$_POST['tdate'];?></h4>
                       <table class="table table-sm table-striped table-hover" >
<thead>
 <tr>
      <th scope="col">S_No</th>
      <th scope="col">Customer Name</th>
      <th scope="col">Transaction Number</th>
      <th scope="col">Action</th>
   
  </tr>
  </thead>
  <tbody>
      <?php $sn_count = 1;
      if ( $transacts == '' ){ echo '<tr><td>No Record Found</td></tr>';} foreach ($transacts as $transacts): ?>
 
      <tr>
      <td><?php htmlout($sn_count); ?></td>
      <td><?php htmlout($transacts['customername']); ?></td>
      <td>#<?php htmlout($transacts['id']); ?></td>
      <td> 
      <div class="listuser">
           <form action="" method="post" >
             <input type="hidden" name="transactionid" value="<?php htmlout($transacts['transactionnumber']); ?>">
              <input class="btn btn-sm btn-primary" type="submit" name="action" value="<?php echo $action ?>">
              <?php if ($action == "Credit Detail") { echo '
              <input class="btn btn-sm btn-success" type="submit" name="action" value="Pay Credit" ';} ?>
            </div>
           </form>
      </td>
     
    </tr>
  
      <?php $sn_count = $sn_count + 1; 
        endforeach; ?>
         </tbody>
       </table>
            </div><!-- /.container-->                
        </div><!-- /#wrap -->
      <?php include '../../includes/footer.html.php'?>
        
         <script src="../../js/ie10-viewport-bug-workaround.js"></script>
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    </body>
</html>