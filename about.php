<!DOCTYPE html>
<html lang="de">
<?php
include('./config.php');
include('./inc/head.php');
?>
<body onload="ladezeit()">

<div id="wrap">
  
<header>
  <h1 id="headline"><?= SITE_NAME ?></h1>
  <ul id="headnav">
    <li><a href="index.php">home</a></li>
    <li><a href="about.php">about</a></li>
    <?php if (HEADER_LOGIN == "1") {
      echo '<li><a href="admin/">admin</a></li>';
    } ?>
    
  </ul>
  
  <div class="clear"></div>
  
</header>  
  
<div id="content">

  <div class="content_item">
    
    <h3>Entwickeler</h3>
    M. Schulz<br />
    M. Vattersen<br />
    <br />
    
    <h3>Externe Verweise</h3>
    <p><a href="http://www.datatables.net/">http://www.datatables.net/</a> Javascript HTML Tabellen sortierung.</p>
    <p><a href="http://www.codeplex.com/PHPExcel">http://www.codeplex.com/PHPExcel</a> Library zum Verarbeiten von xlsx Dateien.</p>
    
  </div><!-- close #content_item -->
  
  
</div><!-- close #content -->

<?php include('./inc/footer.php'); ?>  

</div><!-- close #wrap -->
  
</body>
</html>
