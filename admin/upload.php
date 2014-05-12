<!DOCTYPE html>
<html lang="de">

<?php
include('./auth.php');
include('../config.php');
include('../inc/class/connector.php');
include('../inc/head.php');

$mydb = new connector(SQL_PATH,SQL_USER,SQL_PASSWD,SQL_NAME);
$prefix = "xlsx";
$tables = array("mskeys");

//If the form is submitted
if(isset($_POST['submitted'])) {

    $fileError = array();
    $fileMsg = array();
    
    // need valid xls file
	if(empty($_FILES['file']['tmp_name']))  {
	    // File Empty
		$fileError[] = " Sie haben keine Datei angegeben!";
		$hasError = true;
	} else if ($_FILES['file']['type'] != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") { 
	    // File not valid xls
		$fileError[] = "<b>" . trim($_FILES['file']['name']) . "</b> ist keine g&uuml;ltige ".$prefix." Datei!";
		$hasError = true;
	} else {
	    // File check ok
		$file = trim($_FILES['file']['tmp_name']);
		$fileMsg[] = "Datei <b>" . trim($_FILES['file']['name']) . "</b> wurde erfolgreich gepr&uuml;ft!";
		$hasMsg = true;
		$upload = true;
	}

    $date = $_POST['date'];
    
    if(!isset($hasError)) {
        
        $uploadArr = array(); // Message Array
        $CellAttr = array(); // Cell Highlights Array
        $emptyFSum = 0;
        $emptyASum = 0;
        $emptyTotal = 0;
        
        /** Include path **/
        set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');

        /** PHPExcel_IOFactory */
        include 'PHPExcel/IOFactory.php';

        foreach ($tables as $key => $table) { $mydb->query("DELETE FROM " . $tables[$key] . ";"); }
        // GENERATED ALWAYS AS IDENTITY (START WITH 1 INCREMENT BY 1 MAXVALUE 999999 CACHE 20 NO ORDER)
        $mydb->query("CREATE TABLE mskeys ( id INT(11) NOT NULL AUTO_INCREMENT, name VARCHAR(500), mskeys VARCHAR(500), created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, use_until TIMESTAMP NULL, usable INT(11), used INT(11), user INT(11) NOT NULL REFERENCES users(id), PRIMARY KEY (`id`) )ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');//excel2007
        $objPHPExcel = $objReader->load($file); //ARCHIVE excel2007 dir

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
        {
            $emptyField = 0;
            $emptyAttr = 0;
            // $worksheet->getTitle()
                       
            foreach ($worksheet->getRowIterator() as $row) 
            {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

                // $row->getRowIndex() 
                if (($row->getRowIndex()) > 1)
                {
                    
                    $query = "INSERT INTO " . $tables[0] . " (";
                    $i = 1;
                    
                    // Attribute
                    foreach ($CellAttr as $cellHigh) 
                    {
                        
                        $query .=  " " . $cellHigh;
                        
                        $count = count($CellAttr);
                        if ($count == $i) {
                            $query .= " ";
                            $i = 1;
                        } else {
                            $query .= ", ";
                        }
                        $i++;
                    }
                    
                    $query .= ") VALUES ( ";
                    
                    // Werte
                    foreach ($cellIterator as $key =>$cell)
                    {
                        
                        if (!is_null($cell)) 
                        {           
                            if ($cell == '')
                            {   
                                $emptyField=$emptyField+1;
                                $query .=  " ''";
                                //continue;
                            } else {
                            	if ($key == 0) {
                            		$query .=  " " . $cell->getCalculatedValue();
                            	} else {
                            		$query .=  " '" . $cell->getCalculatedValue() . "'";
                            	}
                                
                            }      
                            
                            if ($count+1 <= $i ) 
                            {   
                                $query .= " ";
                                $parser = true;
                                break;
                            } else {
                                $query .= ", ";
                            }
                            $i++;
                        }
                        
                    }
                    $query .= ")";
                    $mydb->query($query);
                    
                    $uploadArr[] = $query;
                    
                } else {
                    
                    // fill Attributs in array
                    foreach ($cellIterator as $key => $cell)
                    {
                        if ((!is_null($cell))  ) 
                        {   
                            if ($cell == '')
                            {
                                $emptyAttr=$emptyAttr+1;
                                
                                continue;
                            }
                            // Fill Attribute in array 
                            $CellAttr[] = $cell->getCalculatedValue();              
                            
                        }
                    }
                }
            }
            
            $emptyFSum = $emptyFSum + $emptyField;
            $emptyASum = $emptyASum + $emptyAttr;
            $emptySum = $emptyField + $emptyAttr;
            $emptyTotal = $emptyTotal + $emptySum;
            
            $fileMsg[] = "Um <b>" . date('H:i:s') . "</b> wurde <b>" . $worksheet->getTitle() . "</b> mit maximalem Speicherverbrauch von: <b>" . (memory_get_peak_usage(true) / 1024 / 1024) . " MB</b> verarbeitet.\r\n (entf.: <b>".$emptyAttr."</b>/<b>".$emptyField."</b>/<b>".$emptySum."</b>)";
            
        }
        
        $fileMsg[] = "(Leere Datens&auml;tze entfernt: &Uuml;berschriften <b>".$emptyASum."</b> / Felder <b>".$emptyFSum."</b> / gesamt <b>".$emptyTotal."</b>)";
        $dbquery = true;
        
    } // Error parser
    
} // Close submit  


?>

<body onload="ladezeit()">

<div id="wrap">

<?php include('headnav.php'); ?>

<div id="content">

  <div class="content_item">


    <?php if((isset($upload) && $upload == true) && (isset($parser) && $parser == true) && (isset($dbquery) && $dbquery == true)) { ?>
		  	  <meta http-equiv="refresh" content="<?= WAIT ?>; url=index.php" /> 
          <h3>Importassistent</h3>
          
          <p class="valid fade-in">Upload und Import erfolgreich abgeschlossen.</p>
          <br />
          
          <?php if((DEBUG == "1") && (isset($hasMsg))) { ?>
            <p class="info"><?php foreach ($fileMsg as $message) { echo $message . "<br />\n"; } ?></p>
            <br />
          <?php } ?>
          
          <?php if((DEBUG == "1") || (DEBUG == "2")) { ?>
            <p class="info" style="margin-bottom:50px;">
              <?php  
                foreach ($uploadArr as $key => $item)
                {
                    echo $key + 1 . " - " . $uploadArr[$key] . "<br />\n";
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
        <h3>Importassistent</h3>
        
	  
	    <?php if(isset($hasError)) { ?>
        <p class="invalid fade-in"><?php
           foreach ($fileError as $error)
           {
             echo $error . "<br />\n"; 
           }
            
          ?>
        </p>
        <br />
        <br />
        
      <?php } else { ?>
      	<?php if (is_file('../product_key_example.xlsx')) { ?>
					<p class="warning fade-in">Die Datei <a href="../product_key_example.xlsx">product_key_example.xlsx</a> kann als Beispiel für das Excel Schema verwendet werden!</p><br />
				<?php } ?>
      <?php } ?>

	
        <form enctype="multipart/form-data" action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
        
        <!-- Versteckte Infos -->
        <input type="hidden" name="date" id="date" value="<?= $date ?>" />
          
          <div class="formblock">
		    <input type="file" name="file" id="file" value="<?php if(isset($_POST['file'])) echo $_POST['file'];?>" class="txt requiredField <?php if($fileError != '') { echo inputError; } ?>" 
		    placeholder="Datei durch klicken ausw&auml;hlen."
		    onfocus="if(this.value=='Datei durch klicken ausw&auml;hlen.')this.value=''" 
            onblur="if(this.value=='') this.value=this.defaultValue;" />
            
		    <?php if($fileError != '') { ?>
		    <span class="error"><?= $fileError[0] ?></span> 
		    <?php } ?>
		    
	      </div>
	      <br />
        
          <div class="formblock">
            <input type="button" value="Zur&uuml;ck" onclick="window.history.back()" />
	        <button name="submit" type="submit" class="subbutton" onclick="return(confirm('Wirklich Fortsetzen? \nBestehende  Einträge werden dabei gelöscht!'))">Absenden</button>
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






