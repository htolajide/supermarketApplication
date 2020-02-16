

<?php include_once '../../includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage Sales</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="../../css/reset.css" type="text/css" />
        <link rel="stylesheet" href="../../css/style.css" type="text/css" />
         <link rel="icon" href="../../images/favicon.ico">

    <title><?php htmlout($pageTitle); ?></title>

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
                            <li><?php include '../logout.inc.html.php'; ?></li>
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
        <div class="alert alert-info alert-dismissible fade show" role="alert">
         <a href="."><img src="../../images/back.png" alt="backicon" /></a>
            <ul>
                 <li><?php echo $pageTitle; ?></li>
            </ul>
        </div>
    <div class="container border border-secondary rounded">
    
     <form id= "action-form" action=?<?php htmlout($action); ?> method="post" >
         
    <div class="row">
    <div class="col-md-3 mb-3">
      <label for="validationDefault01">Product Name:</label>
      <input type="text" name="name" class="form-control" id="validationDefault01" placeholder="Name"  value="<?php htmlout($name); ?>" required>
    </div>
    <div class="col-md-3 mb-3">
      <label for="validationDefault02">Product Quantity:</label>
      <input type="text" name="quantity" class="form-control" id="validationDefault02" placeholder="Quantity" value="" required>
    </div>
  </div>
    <div class="row">
    <div class="col-md-3 mb-3">
      <label for="validationDefault03">Product Price:</label>
      <input type="text" name="price" class="form-control" id="validationDefault03" placeholder="Price"  value="<?php htmlout($price); ?>" required>
    </div>
    <div class="col-md-3 mb-3">
      <label for="brand">Product Brand:</label>
        <select name="brands" id="inputState" class="form-control md-3" >
          <option value="">Select one</option>
          <?php foreach ($brands as $brand): ?>
            <option value="<?php htmlout($brand['id']); ?>"<?php
               if ($brand['selected'])
              {
                echo 'selected';
              }
                ?>>
                    <?php htmlout($brand['name']); ?></option>
          <?php endforeach; ?>
        </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3 mb-3">
          
      <label for="category">Product Category:</label>
        <select name="categories"  id="inputState" class="form-control md-3">
          <option value="">Select one</option>
          <?php foreach ($categories as $category): ?>
            <option value="<?php htmlout($category['id']); ?>"<?php
               if ($category['selected'])
              {
                echo 'selected';
              }
                ?>>
                    <?php htmlout($category['name']); ?></option>
          <?php endforeach; ?>
        </select>
        
    </div>
    <div class="col-md-3 mb-3">
       <label for="user">Name of User:</label>
        <select name="users"id="inputState" class="form-control md-3">
          <option value="">Select one</option>
          <?php foreach ($users as $user): ?>
            <option value="<?php htmlout($user['id']); ?>"<?php
               if ($user['selected'])
              {
                echo 'selected';
              }
                ?>>
                    <?php htmlout($user['name']); ?></option>
          <?php endforeach; ?>
        </select>
      
    </div>
  </div>
  <div class="row">
      
    <div class="col-md-3 mb-3">
         <label for="Sources">Source Branch:</label>
        <select name="sources" id="inputState" class="form-control md-3">
          <option value="">Select one</option>
          <?php foreach ($sources as $source): ?>
            <option value="<?php htmlout($source['id']); ?>"<?php
               if ($source['selected'])
              {
                echo 'selected';
              }
                ?>>
                    <?php htmlout($source['name']); ?></option>
          <?php endforeach; ?>
        </select>
       
    </div>
      <div class="col-md-3 mb-3">
            <label for="Destinations">Destination Branch:</label>
        <select name="destinations" id="inputState" class="form-control md-3">
          <option value="">Select one</option>
          <?php foreach ($destinations as $destination): ?>
            <option value="<?php htmlout($destination['id']); ?>"<?php
               if ($destination['selected'])
              {
                echo 'selected';
              }
                ?>>
                    <?php htmlout($destination['name']); ?></option>
          <?php endforeach; ?>
        </select>
        
      </div>
      </div>
  
        <div class="row">
        <div class="col-md-3 mb-3">
            <label for="credit">Credit:</label>
             <select name ="credit"id="inputState" class="form-control md-3" value="<?php htmlout($credit); ?>" >
                <option value="0"<?php if ($credit == '0') echo 'selected = "selected"'; ?>>Cash Payment</option>
                <option value="1"<?php if ($credit == '1') echo 'selected = "selected"'; ?>>Credit</option>
            </select> 
       
        </div>
        <div class="col-md-3 mb-3">
         <label for="submit">Press The Buttom</label> 
        <input class="btn btn-primary form-control " type="submit" value="<?php htmlout($button); ?>">
       
        </div>
      </fieldset>
        </div>
      <div>
        <input type="hidden" name="id" value="<?php htmlout($id); ?>">
 
   
     </div>
  


   </form>
   </div>
 
</div>
        </div><!-- Container -->
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

