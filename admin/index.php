<!DOCTYPE html>
<html>
    <head>
        <title>Admin Home Page </title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="../css/reset.css" type="text/css" />
        <link rel="stylesheet" href="../css/style.css" type="text/css" />
        <script src="js/jquery.js"></script> 
         <link rel="icon" href="../images/favicon.ico">
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
                             
                             <li><a href="../">Home</a></li>
                             <li><a class="active" href=".">Admin Home</a></li>
                             <li ><a href="products">Manage Products</a></li>
                             <li><a href="users">Manage Users</a></li>
                             <li><a href="sales">Manage Sales</a></li>
                             
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
            <div class="container"> 
                
                <div class="showcase-right">
                   
                    </div><!-- /.showcase-right-->
                    <div class="showcase-left">
                        <h1>Welcome to Veroyori Admin Homepage</h1>
                    <p>
                       Here, you will be able to manage the various day to day activities that are carried out at our outlets. These activities include but not limited to managing users information, product informations and sales informations 
                    </p>
                    <p>
                        However, before you can perform any activity here you must either be the administrator or manager or any accredited member of staff. Your login credential must be correct. Thank you for your anticipated compliance. 
                    </p>
                    </div><!-- /.showcase-left-->
               
                <div class="clr"></div>
            </div><!-- /.container-->                
        </div><!-- /#wrap -->
          <?php include '../includes/footer.html.php'?>
    </body>
</html>