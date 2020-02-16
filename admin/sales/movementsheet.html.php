 
 
<?php include_once '../../includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Movement Sheet</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="../../css/reset.css" type="text/css" />
        <link rel="stylesheet" href="../../css/style.css" type="text/css" />
        <link rel="icon" href="../../images/favicon.ico">
        <script language="JavaScript1.2"> 
            function printFunc() {
       var divToPrint = document.getElementById('printarea');
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

    <title>User Login</title>

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
                       <ul> <li><a class="active" href=".">Move Sheet</a></li>
                            <li><a href=".">Back To Control Panel</a></li>
                            <li><?php include '../logout.inc.html.php'; ?></li>
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
            <div class="container">
                
           
              <div id='printarea'>  
            <?php if (count($cart) > 0): ?>
          
    
                    <h4>Veroyori Ventures Iseyin<br>
                    08064828003, 08069758865</h4>
               <div class="pull-left">
                    <h5><?php echo ' Cashier: ';
                     htmlout($_SESSION['name']); ?></h5>
                     <h5><?php
                    echo ' Attendant: ';
                    htmlout($row['customername']);?></h5>
                    <h5><?php echo ' Moved From: ';
                     htmlout($_SESSION['source']); ?></h5>
                    
                </div>
                <div class="pull-right">
					<h5><?php
                    echo 'Date: ';
                      htmlout($row['tdate']); ?></h5>
                    <h5><?php echo 'Branch: ';
                     htmlout($_SESSION['source']); ?></h5>
                      <h5><?php echo ' Moved To: ';
                     htmlout($_SESSION['destination']); ?></h5>
               </div>
               
               <table class="table table-sm table-striped ">
  <thead>
    <tr>
      <th colspan=3>Item Description</th>
      <th scope="col">Qty</th>
    </tr>
  </thead>
  <tfoot>
<tr>
<td colspan=3>Number of Items</td>
<td><?php echo $total; ?></td>
</tr>
</tfoot>
  <tbody>
      <?php foreach ($cart as $moves): ?>
 
      <tr>
      <td colspan=3><?php htmlout($moves['brandname']); 
      echo ' ';
      htmlout($moves['productname']);
      echo ' ';
      htmlout($moves['categoryname']); ?></td>
      <td><?php htmlout($moves['quantity']); ?></td>
    </form>
    </tr>
  
      <?php endforeach; ?>
         </tbody>
       </table>
                <?php else: ?>
<p>Your sheet is empty!</p>
<?php endif; ?>
<H4<?php $today=new DateTime;
echo $today->format('Y-m-d H:i:s').' '. 'TRN: #'.$row['id'] ?></H4>
<H4><a href="<?php echo $back ?>">Back</a> | <input type='button' class='btn btn-primary' id="btn" value='Print' onclick='printFunc();'></H4>
</div>
</form>
  </div><!-- Container -->
          
</div><!-- /#wrap -->
      
</html>

