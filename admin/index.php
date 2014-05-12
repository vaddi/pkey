<!DOCTYPE html>
<html lang="de">
<?php 
include('./auth.php');
include('../config.php');
include('../inc/class/connector.php');
include('../inc/head.php'); 

// DB Login
$mydb = new connector(SQL_PATH,SQL_USER,SQL_PASSWD,SQL_NAME);

?>
<body onload="ladezeit()">

<div id="wrap">

<?php include('headnav.php'); ?>

<div id="content">

  <div class="content_item">

<?php
// DB use table
$mydb_tab = "mskeys";

// DB Query
//$mydb->query('SELECT * FROM '.$mydb_tab );
//$mydb->query($query);

$cboxArr = array();
if(isset($_POST['submitted']) && count($_POST['marked']) > 0) {
	
	$total_marked = count($_POST['marked']);
	$unusable = array();
	
	echo '<meta http-equiv="refresh" content="'.WAIT.'; url=index.php" />';
	echo '<p class="warning fade-in">ID';
	if($total_marked > 1) echo 's'; 	
	echo ' marked: ';
	foreach ($_POST['marked'] as $key => $item) {
//		$query = "DELETE FROM ".$mydb_tab." WHERE id = '".$item."';";
		$query = "SELECT * FROM ".$mydb_tab." WHERE id = '".$item."';";
		$mydb->query($query);
		$res = $mydb->fetchArr();
		
		$counted = $res[6] + 1;
		if($counted >= $res[5]) $counted = $res[5];
		
		if(($res[5] - $counted) == 0) {
			$unusable[] = $item;
		} 
		
		if($key > 0 && $key != $total_marked) echo ", ";
		echo $item;
		
		$query = "UPDATE ".$mydb_tab." SET used = '".$counted."' WHERE id = '".$item."';";
    $mydb->query($query);
    
    if($key == $total_marked -1) {
			if($unusable > 0) {
				foreach ($unusable as $value)	{
					
					echo "<br /><b>" . $value . "</b> is now marked as unusable!";
				}
			}
    	echo "<br />";
    }
	}
	echo '</p>';
	echo '<br />';

} // End submitted

?>


<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">

<?php

// DB Query for the View
$mydb->query('SELECT id, name, mskeys, use_until, used, usable FROM '.$mydb_tab.' where used < usable');

// Anzahl Felder
$res = $mydb->mysql_num_fields();
// Attribute Array befüllen
$i = 0;
$AttrArr = array();
while ($i < $res) 
{
    $AttrField = $mydb->mysql_field_name($i);
    $AttrArr[] = $AttrField;
    $i++;
}

// Attribute Array ausgeben
echo "<thead>\n";
echo "<tr>\n";

foreach($AttrArr as $item)
{
  echo "  <th>".$item." </th>\n";
}

echo "  <th> + </th>\n";

echo "</tr>\n";
echo "</thead>\n\n";

echo "<tbody>\n";

$evod = "even";
// Anzahl der Zeilen/Rows
$count = $mydb->count();

$itemArr = array();


for ($i = 0; $i < $count; $i++ )
{
    
    // Simlpe Evenodd
    if ($evod == "even") 
    {
        $evod = "odd";
    } else {
        $evod = "even";
    }
    
    echo "<tr class=".$evod.">\n";
 		
 		$elcount = 0;
    foreach($mydb->fetchRow() as $key => $item)
    {
        echo "<td>".$item." </td>\n";
        
        if($elcount == 0)
        {
          $keyvar = $item;
        }
        
        //if(($key % $res) == 0)
        if ($key == $AttrArr[count($AttrArr) -1])
        {
            echo '<td> <input type="checkbox" name="marked[]" value="'.$keyvar.'" onclick="unselectCheckBoxUnik()" class="checkBoxInLoop" /></td>'."\n";
        
        }
        $elcount++;
    }
    
       
            
    
    echo "</tr>\n";
}

echo "</tbody>\n";


?>

</table>

  <div style="clear:both;margin:18px 0 0 0;">
	<button name="submit" type="submit" class="subbutton">Markieren</button> Alle: <input type="checkbox" onclick="selectAllCheckBoxes(this)" id="checkBoxUnik"/>
	<input type="hidden" name="submitted" id="submitted" value="true" />
  </div>


</form>

<?php 

// Disconnect DB
$mydb->disconnect();

?>

</div><!-- close #content_item -->
  
  
</div><!-- close #content -->

<?php include('../inc/footer.php'); ?>  

</div><!-- close #wrap -->
  
</body>
</html>
