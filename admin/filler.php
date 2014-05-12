<!DOCTYPE html>
<html lang="de">

<?php
include('./auth.php');
include('../config.php');
include('../inc/class/connector.php');
include('../inc/head.php');
$amount_default = 25;
$amountError = '';
$amount;

// DB Login 
$mydb = new connector(SQL_PATH,SQL_USER,SQL_PASSWD,SQL_NAME);

// Get amount of db entries
//	$mydb->query('SELECT id FROM mskeys Order By id desc Limit 1 '); 
//	$res = $mydb->fetchArr();
//	$num_db = $res[0];
//	$num_total = $num_db + $amount;
//  echo "db:" . $num_db . " amount:" . $amount . " total: " . $num_total;
//  
$FillArr = array();
if(isset($_POST['submitted'])) {

  // set amount of entries
	if(trim($_POST['amount']) === '') {
	    $amount = $amount_default;
	} else {
	    $amount = trim($_POST['amount']);
	    $CountArr = 1;
	}
  
  function array_random($arr, $num = 1) {
     shuffle($arr);
 
      $r = array();
      for ($i = 0; $i < $num; $i++) {
          $r[] = $arr[$i];
          }
      return $num == 1 ? $r[0] : $r;
  }

  $randNames = array("MS Office 2007","MS Office 2010","MS Windows 7","MS Windows XP","MS Windows Vista","MS VisualStudio 2008","MS VisualStudio 2010");
  $randNum = array("1","3","5","10","15","20","25");
	
	function randKey($strgs = 0, $blocks = 0) {
		$rnd_strings = array( 'a','b','c','d','e',
													'f','g','h','i','j',
													'k','l','m','n','o',
													'p','q','r','s','t',
													'u','v','w','x','y',
													'z','0','1','2','3',
													'4','5','6','7','8','9'
												);
		
		$randKey = "";
		if($blocks == 0) $blocks = rand(0,5);
		for ($i = 0; $i < $blocks; $i++)	{
			if($strgs == 0) $strgs = rand(0,5);
			for ($j = 0; $j < $strgs; $j++) {
				
				$randKey .= strtoupper($rnd_strings[rand(0,count($rnd_strings))]);
				
			}
			if($i < $blocks -1 ) $randKey .= "-";
		}
		
		if( substr($randKey,1) == '-' || $randKey == '---' || $randKey == '--' || $randKey == '-' || $randKey == '') $randKey = randKey($strgs, $blocks);
		
		return $randKey;
	}
	
	function randomDate($start_date, $end_date)	{
			// Convert to timetamps
			$min = strtotime($start_date);
			$max = strtotime($end_date);

			// Generate random number using above bounds
			$val = rand($min, $max);

			// Convert back to desired date format
			return date('Y-m-d H:i:s', $val);
	}
	
	// Get amount of db entries
	$mydb->query('SELECT id FROM mskeys Order By id desc Limit 1 '); 
	$res = $mydb->fetchArr();
	$num_db = $res[0];
	if($num_db == 0 || $num_db == '' || $num_db == NULL) { 
		$num_db = 1;
	} else {
		$num_db = $num_db + 1;
	}
	
	$num_total = $num_db + $amount;
	
	// Main loop
	$counter = 1;
  for ($i = $num_db; $i < $num_total; $i++)
  {
    $ProdName = array_random($randNames);
    $ProdNum = array_random($randNum);
    $datetmp = date("Y-m-d H:i:s");
    $query = "INSERT INTO mskeys (id, name, mskeys, created, use_until, usable, used, user) 
    					VALUES ( '".$i."', '".$ProdName."', '".randKey(5,5)."', '".randomDate('2013-05-11',date("Y-m-d H:i:s"))."', '".randomDate(date("Y-m-d H:i:s"),'2014-05-11')."', '".$ProdNum."', '0', '1')";
    $mydb->query($query); 
    $FillArr[] = $query;

    $CountArr = $counter++;
  }
  
  $FillStat = true;
}

?>

<body onload="ladezeit()">

<div id="wrap">

<?php include('headnav.php'); ?>

<div id="content">

  <div class="content_item">


    <?php if(isset($FillStat) && $FillStat == true) { ?>
        <meta http-equiv="refresh" content="<?= WAIT ?>; url=index.php" />  
          <h3>Bericht:</h3>
          
          <p class="warning fade-in">Bef&uuml;llen erfolgreich abgeschlossen.</p>
          <br />
          <?php if(DEBUG >= "1") { ?>
		        <p class="info" style="margin-bottom:50px;">
		          <?php  
		          echo $CountArr ." Datens&auml;tze geschrieben <br />";
		            foreach ($FillArr as $key => $item)
		            {
		                echo $FillArr[$key] . "<br />\n";
		            }
		        
		          ?>
		        </p>
          <?php } ?>
        <?php } else { ?>
        
        
        <div id="install-form">
	    <?php $date = date("Y-m-d H:i:s"); ?>
	    <span class="right"><?= $date ?></span>
        <h3>F&uuml;llassistent</h3>
        
        <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
        
        <!-- Versteckte Infos -->  
        <input type="hidden" name="date" id="date" value="<?= $date ?>" />
        
          <div class="formblock">
		    <input type="text" name="amount" id="amount" value="<?php if(isset($_POST['amount'])) echo $_POST['amount'];?>" class="txt requiredField <?php if($amountError != '') { echo inputError; } ?>" 
		    placeholder="<?= $amount_default ?>"
		    onfocus="if(this.value=='<?= $amount_default ?>')this.value=''" 
            onblur="if(this.value=='') this.value=this.defaultValue;" />
		    <?php if($amountError != '') { ?>
		    <span class="error"><?php echo $amountError;?></span> 
		    <?php } ?>
	      </div>
        
          <div class="formblock">
            <input type="button" value="Zur&uuml;ck" onclick="window.history.back()" /> 
	        <button name="submit" type="submit" class="subbutton">DB f&uuml;llen</button>
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







