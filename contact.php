
<!DOCTYPE html>
<html>
    <head>
        <title>Contact Page</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="css/reset.css" type="text/css" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
        <script src="js/jquery.js"></script> 
         <link rel="icon" href="images/favicon.ico">
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

  myimages[1]="images/verologo00.png";
  myimages[2]="images/verologo01.png";
  myimages[3]="images/verologo02.png";
 
  number=1;
     
  function animate(){
      
   
   for(i=1; i<3; i++){ document.image_rotate.src = myimages[number];
     number++;
     if (number > 3) number=1;
   }
    
}

</script>
     
          
                
             <img name="image_rotate" src="images/verologo00.png" onLoad="setTimeout('animate()', delay);" alt="verologo" />
             
                    </div><!-- /.logo -->
                    <nav class="main-nav">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="admin/">Admin</a></li>
                           
                            <li><a class="active" href="contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
            <div class="container">                
                <div class="clr"></div>
                <div id="content">
                    <h1>Veroyori Contacts</h1>
                    <p>
                      You can contact Veroyori on 08069758865 
                    </p>
                    <p>
                        Also on 08064828003
                  

                    </p>
                </div><!-- /.content -->
             
                <div class="clr"></div>
            </div><!-- /.container-->                
        </div><!-- /#wrap -->
          <?php include 'includes/footer.html.php'?>
    </body>
</html>