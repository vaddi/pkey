<!DOCTYPE html>
<html lang="de">

<?php

include('../config.php');
include('../inc/class/connector.php');
include('../inc/head.php');

$nameError = '';
$emailError = '';
$passwordError = '';


//If the form is submitted
if(isset($_POST['submitted'])) {

    // require a name from user
	if(trim($_POST['contactName']) === '') {
		$nameError =  'Bitte geben Sie einen Namen ein!'; 
		$hasError = true;
	} else {
		$name = trim($_POST['contactName']);
		//$name = str_replace($r1, $r2, $name);
		//$name = str_replace('[^\w\s[:punct:]]*', ' ', $name);

	}

    // need valid email
	if(trim($_POST['email']) === '')  {
		$emailError = 'Bitte geben Sie eine g&uuml;ltige email Adresse ein.';
		$hasError = true;
	} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
		$emailError = 'Sie haben keine g&uuml;ltige email Adresse eingegeben.';
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}


  // require a password from user
	if(trim($_POST['password']) === '') {
		$passwordError =  'Bitte geben Sie ein Passwort ein!'; 
		$hasError = true;
	} else if (strlen(trim($_POST['password'])) < 3)
	{
	    $passwordError =  'Ihr Passwort sollte mindestens 3 Zeichen Lang sein!'; 
		$hasError = true;
	    
	} else {
		$password = trim($_POST['password']);
	}
	
	if(!isset($hasError)) {
	
		$mydb = new connector(SQL_PATH,SQL_USER,SQL_PASSWD,SQL_NAME);
    
    $query = "use ". SQL_NAME .";";
    $mydb->query($query);
    
    $query = "SHOW tables;";
    $mydb->query($query);
    
		$res = $mydb->mysql_num_fields();		
		
		if(!isset($res)){
			$setupArr[] = $query;
		  $hasError = true;
		  $sqlError[] = 'Datenbank '. SQL_NAME .' existiert noch nicht!' . $res;
		}
    
    
	}
	
  $date = $_POST['date'];
  
  if(!isset($hasError)) {
  
    $setupArr = array();
    
			
			// DB Tabellenschema (ohne Beispieldaten) in einem Array
    $tables = array(
      "users"=> array(
                  "id" => array("INT(11) NOT NULL AUTO_INCREMENT", "1"),
                  "name" => array("VARCHAR(100)", $name),
                  "pwd" => array("VARCHAR(100)", $password),
                  "email" => array("VARCHAR(100)", $email),
                  "created" => array("TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP", $date),
                  "valid" => array("SMALLINT UNSIGNED", "1")
      ),
        
      "mskeys" => array(
                  "id" => array("INT(11) NOT NULL AUTO_INCREMENT", ""),
                  "name" => array("VARCHAR(500)", "first entry"),
                  "mskeys" => array("VARCHAR(500)", "no-key"),
                  "created" => array("TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP", date("Y-m-d H:i:s")),
                  "use_until" => array("TIMESTAMP NULL", ""),
                  "usable" => array("INT(11)", "5"),
                  "used" => array("INT(11)", "0"),
                  "user" => array("INT(11) NOT NULL REFERENCES users(id)", "1")
      )
            
    );

    // Tabellen iterieren und daraus DB CREATE Query erstellen
    // Die beiden Arrays $ctab $cfield sind dann passend befüllt (tabellenname/anzahl Felder) 
    foreach ($tables as $key0 => $table) {   
        $query = "CREATE TABLE ".$key0." ( ";
        $ctab[] = $key0;
        $i = 1;
        $first = '';
        foreach ($table as $key1 => $row) {
        	
        	if($i == 1) {
        		$first = $key1;
        	}
        	
          $query .= $key1 . " " . $row[0];
          $count = count($table);
          if ($count == $i) {
              $query .= "";
              $cfield[] = $i;
          } else {
              $query .= ", ";
          }
          $i++;
        }
        $query .= ', PRIMARY KEY (`'.$first.'`) )ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';
        //echo "<br />\n";
        
        $mydb->query($query);
        //$setupArr[] = "Tabelle ".$key0." erfolgreich erstellt.";
        $setupArr[] = $query;
    }


		foreach ($tables as $key => $value) {
			$query = "DESCRIBE ".$key.";";
		  $mydb->query($query);
			$res = $mydb->mysql_num_fields();		
		
			if(!isset($res)){
				$setupArr[] = $query;
				$hasError = true;
				$sqlError[] = 'Tabelle '. $key .' konnte nicht erstellt werden!' . $res;
			}
    }
    

    // Tabellen iterieren und daraus DB INSERT Query erstellen 
    foreach ($tables as $key0 => $table) {   
        $query = "INSERT INTO ".$key0." (";
        $ii = 1;
        foreach ($table as $key1 => $row) {
            $query .= $key1 . " " ;
            $count = count($table);
            if ($count == $ii) {
                $query .= "";
            } else {
                $query .= ", ";
            }
            $ii++;
        }
        $query .= ") VALUES ( ";
        $i = 1;
        foreach ($table as $key1 => $row) {
            $query .= " '" . $row[1];
            $count = count($table);
            if ($count == $i) {
                $query .= "'";
            } else {
                $query .= "', ";
            }
            $i++;
        }
        $query .= ' );';
        //echo "<br />\n";
        $mydb->query($query);
        //echo $query;
        //$setupArr[] = "Tabelle ".$key0." erfolgreich gefüllt.";
        $setupArr[] = $query;
    }
    //echo "<br />\n";
    
    foreach ($tables as $key => $value) {
			$query = "SELECT * FROM ".$key." WHERE id = '1';";
		  $mydb->query($query);
			$res = $mydb->mysql_num_fields();		
		
			if(!isset($res)){
				$setupArr[] = $query;
				$hasError = true;
				$sqlError[] = 'Tabelle '. $key .' konnte nicht gefüllt werden!' . $res;
			}
    }
    
    if(!isset($hasError)) {
			
			// Delete test data
			$query = "DELETE FROM ".array_pop(array_keys($tables))." WHERE id = '1';";
		  $mydb->query($query);
			
  		$setup = true;
  	}
        
  } // Error parser
    
} // Close Post


?>

<body onload="ladezeit()">

<div id="wrap">


<header>
  <h1 id="headline"><a href="<?= BASE . 'admin/index.php' ?>"><?= SITE_NAME ?></a></h1>
  
  <div class="clear"></div>
  
</header>

<div id="content">

  <div class="content_item">


    <?php if(isset($setup) && $setup == true) { ?>
  	  <meta http-equiv="refresh" content="<?= WAIT ?>; url=index.php" /> 
      <h3>Setup Bericht</h3>
      
      <p class="valid fade-in">Installation erfolgreich abgeschlossen.</p><br />
				<?php if(DEBUG >= "1") { ?>
		      <p class="info">
		        <?php  
		          foreach ($setupArr as $key => $item)
		          {
		              echo $setupArr[$key] . "<br /><br />\n";
		          }
		        ?>
		      </p>
		    <?php } ?>
      
    <?php } else { ?>
        
	  <div class="desc">
	    <p class="desc"> </p>
	  </div>
				
	  <div id="install-form">
    <?php $date = date("Y-m-d H:i:s"); ?>
    <span class="right"><?= $date ?></span>
    <h3>Installationsassistent</h3>
      
 	  <?php if(isset($hasError)) { ?>
 	  	<?php if(count($sqlError) == 1) { ?>
    	  <p class="invalid fade-in"><?= $sqlError[0] ?></p><br />
      <?php } else { ?>
      	<p class="invalid fade-in">Bitte geben Sie Ihre Daten in die vorgesehenen Felder ein und klicken anschlie&szlig;end auf Absenden. <br />Es ist notwendig eine gültige E-Mail Adresse f&uuml;r Systemnachrichten anzugeben.</p><br />
      <?php } ?>
    <?php } ?>
		
		<?php if (SQL_PATH === ""): ?>
	  <p class="invalid fade-in">Die Datei <b>config.php</b> muss zuerst editiert werden!</p><br />
		<?php endif ?>
			
		<div>
			<p>Zum erstellen der Datenbank bitte <b>zuerst</b> die folgenden SQL Befehle an die Datenbank absenden:</p><br />
			<p>
				CREATE DATABASE `<?= SQL_NAME ?>` CHARACTER SET utf8; <br />
				GRANT ALL ON `<?= SQL_NAME ?>`.* TO '<?= SQL_USER ?>'@'<?= SQL_PATH ?>' IDENTIFIED BY '<?= SQL_PASSWD ?>' WITH GRANT OPTION;
			</p>
		</div>
			
			<br />
			<hr class="clear">
			<br />
			
      <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
      
      <!-- Versteckte Infos -->
      <input type="hidden" name="date" id="date" value="<?= $date ?>" />
        
        <div class="formblock">
	    <input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="txt requiredField <?php if($nameError != '') { echo 'inputError'; } ?>" 
	    placeholder="Name:"
	    onfocus="if(this.value=='Name:')this.value=''" 
          onblur="if(this.value=='') this.value=this.defaultValue;" />
	    <?php if($nameError != '') { ?>
	    <span class="error"><?php echo $nameError;?></span> 
	    <?php } ?>
      </div>
        
        <div class="formblock">
	    <input type="mail" name="email" id="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" class="txt requiredField email <?php if($emailError != '') { echo 'inputError'; } ?>" 
	    placeholder="Email:" 
	    onfocus="if(this.value=='Email:')this.value=''" 
          onblur="if(this.value=='') this.value=this.defaultValue;" />
	    <?php if($emailError != '') { ?>
	      <span class="error"><?php echo $emailError;?></span>
	    <?php } ?>
      </div>
      
      <div class="formblock">
	    <input type="text" name="password" id="password" value="<?php if(isset($_POST['password'])) echo $_POST['password'];?>" class="txt requiredField password <?php if($passwordError != '') { echo 'inputError'; } ?>"
	    placeholder="Passwort" 
          onfocus="if(this.value=='Passwort')this.value=''" 
          onblur="if(this.value=='') this.value=this.defaultValue;" />
	    <?php if($passwordError != '') { ?>
	      <span class="error"><?php echo $passwordError;?></span>
	    <?php } ?>
      </div>
      
      <br />
      
        <div class="formblock">
          <input type="button" value="Zur&uuml;ck" onclick="window.history.back()" />
        <button name="submit" type="submit" class="subbutton">Absenden</button>
        <input type="hidden" name="submitted" id="submitted" value="true" />
      </div>
      
      </form>
      </div>
      
    <?php } ?>
	
  </div><!-- close #content_item -->
  
  
</div><!-- close #content -->

<?php include('../inc/footer.php'); ?>  

</div><!-- close #wrap -->
  
</body>
</html>






