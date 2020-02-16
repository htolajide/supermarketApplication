
<!DOCTYPE html>
<html>
    <head>
        <title>Veroyori Homepage</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="css/reset.css" type="text/css" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
        <script src="js/jquery.js"></script> 
         <link rel="icon" href="images/favicon.ico">
        <script type="text/javascript">
            
            var show_width = 1;
            if(show_width == 1){
                $(document).ready(function(){
                    $(window).resize(function(){
                        var screen_width = $(window).width();
                        document.getElementById('screen_width').innerHTML = 'Window Width: ' +screen_width.toString();
                    });   
                });
            }
        </script>
    </head>
    <body>
        <div id="wrap">
            <div id="screen_width"></div>
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
                            <li><a class="active" href="index.php">Home</a></li>
                            <li><a href="admin">Admin</a></li>
                          <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div><!-- /.container-->
            </header> 
            <div id="showcase">
                <div class="container">
                    <div class="showcase-right">
                        <img src="images/vero2.jpg" alt="Veroyori Solutions" />
                    </div><!-- /.showcase-right-->
                    <div class="showcase-left">
                        <h1>Veroyori Home of Varieties</h1>
                        <h3>At Veroyori all your needs will be met</h3>
                        <p>Veroyori ventures as the Home of Healthy Food has become an household name in oyo state and Nigeria as a whole. Trusted brand in Nigeria and for almost a decade now providing food with high quality products for distribution. We are poised to increase our involvement especially in the wider range of distribution of food all over Nigeria and beyond. We strongly believe that ‘food is life’ which quaranteed the growth and development of our great nation.</p>
                        <br />
                        <a href="#"><img src="images/readmore.png" alt="Rad More" /></a>
                    </div><!-- /.showcase-left-->
                </div><!-- /.container-->
            </div><!-- /#showcase -->
            <div class="container">
                <div class="box3">
                    <img src="images/twists.png" alt="Online Marketing" />
                    <h3>Golden Penny Products</h3>
                    <p>You can get any variety of golden penny at any of our outlets</p>
                </div><!-- /.box3-->
                 <div class="box3">
                    <img src="images/dango.png" alt="Golden Penny" />
                    <h3>Dangote Products</h3>
                    <p>As we all know that Dangote is big food products manufacturer, avail yourself any of its products at Veroyori outlets</p>
                </div><!-- /.box3-->
                 <div class="box3">
                    <img src="images/macroni.png" alt="Online Marketing" />
                    <h3>Mama Gold</h3>
                    <p>Mama Gold is also part of our many products which are of high quality for your satisfaction</p>
                </div><!-- /.box3-->
                <div class="clr"></div>
                <div id="content">
                    
                </div><!-- /.content -->
               
                <div class="clr"></div>
            </div><!-- /.container-->                
        </div><!-- /#wrap -->
        <?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
          <?php include 'includes/footer.html.php'?>
    </body>
</html>