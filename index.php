<!DOCTYPE html>
<html lang="de">
<?php
include('./config.php');
include('./inc/class/connector.php');
include('./inc/head.php');

if(MAINSITE != 1) {
echo '<meta http-equiv="refresh" content="0; url=admin/login.php" />';
exit;
}

?>
<body onload="ladezeit()">

<div id="wrap">

<header>
  <h1 id="headline"><?= SITE_NAME ?></h1>
  <ul id="headnav">
    <li><a href="index.php">home</a></li>
    <li><a href="about.php">about</a></li>
    <?php if (HEADER_LOGIN == "1") {
      echo '<li><a href="admin">admin</a></li>';
    } ?>
  </ul>
  
  <script>
		var path = window.location.pathname;
		var pathArr = path.split("/");
		var loc = pathArr[pathArr.length - 1];
		
		if(loc == '') {
			loc = 'index.php';
		}

		$('#headnav').find('a').each(function() {
			$(this).toggleClass('active', $(this).attr('href') == loc);
		});
	</script>
	
  <div class="clear"></div>
  
</header>  
  
<div id="content">

  <div class="content_item">
    
    <h2>Content Bereich</h2>
    
    <p>Hier finden die Inhalte der Startseite ihren Platz.</p>
  </div><!-- close #content_item -->
  
  
</div><!-- close #content -->

<?php include('./inc/footer.php'); ?>  

</div><!-- close #wrap -->
  
</body>
</html>
