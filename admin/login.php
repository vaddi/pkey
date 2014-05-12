<!DOCTYPE html>
<html lang="de">
<?php 
include('../config.php'); 
include('../inc/class/connector.php');
include('../inc/head.php');

?>
<body onload="ladezeit()">

<div id="wrap">

<header>
  <h1 id="headline"><?= SITE_NAME ?></h1>
  <ul id="headnav">
  	<?php if (MAINSITE == "1") { ?>
  		<li><a href="../index.php">home</a></li>
		  <li><a href="../about.php">about</a></li>
		  <?php if (HEADER_LOGIN == "1") {
		    echo '<li><a href="index.php">admin</a></li>';
		  } ?>
  	<?php } else { ?>
  		<!-- default menu -->
    <?php } ?>
  </ul>
  
  <div class="clear"></div>
  
</header>  
  
<div id="content">

  <div class="content_item">

	<?php
	if (SQL_PATH === "") { ?>
		<p class="invalid fade-in">Die Datei <b>config.php</b> muss zuerst editiert werden um die verbindung zur Datenbank herstellen zu k&ouml;nnen!</p><br />
	<?php } else {
		// Erster Query mit test
		$mydb = new connector(SQL_PATH,SQL_USER,SQL_PASSWD,SQL_NAME);
		$mydb->query('SELECT * FROM users');
		if ($mydb->count() == 0) { ?>
			<p class="invalid fade-in">Kein Benutzer in Datenbank angelegt! <br />Wollen sie das <a href="setup.php"><u>Setup</u></a> ausf&uuml;hren?</p>
		<?php }

	}

	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  session_start();

  // http oder https?
  if($_SERVER["SERVER_PORT"] == 80) {
    $http = "http";
  } else if ($_SERVER["SERVER_PORT"] == 443) {
    $http = "https";
  } else {
    // Fallback
    $http = "http";
  }

  $request = $_REQUEST['s'];
  $username = $_POST['username'];
  $passwort = $_POST['passwort'];
  
  $userArr = $mydb->fetchArr();
  
  $validuser = $userArr[1]; // Spalte 1 name
  $validpasswd = $userArr[2]; // Spalte 3 passwd

  $hostname = $_SERVER['HTTP_HOST'];
  $path = dirname($_SERVER['PHP_SELF']);

  // Benutzername und Passwort werden überprüft
  if ($username == $validuser && $passwort == $validpasswd) {
    $_SESSION['angemeldet'] = true;

    // Weiterleitung zur geschützten Startseite
    if ($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1') {
      if (php_sapi_name() == 'cgi') {
        header('Status: 303 See Other');
      } else {
        header('HTTP/1.1 303 See Other');
      }
    }
        
    header('Location: '.$http.'://'.$hostname.($path == '/' ? '' : $path).'/index.php');
    exit;
  }
}

?>

<div id="login_wrap">
  <h3>Login</h3>
  <form action="login.php" method="post">
   <input name="username" placeholder="Benutzername" />
   <input type="password" name="passwort" placeholder="Passwort" /> <br /><br />
   <input type="hidden" name="s" value="<?= $request; ?>" />
   <input type="submit" value="Anmelden" />
  </form>
</div>

</div><!-- close #content_item -->
  
  
</div><!-- close #content -->

<?php include('../inc/footer.php'); ?>  

</div><!-- close #wrap -->
  
</body>
</html>
