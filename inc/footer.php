<footer>

  <div class="left invisible">
    <ul>
      <li>Headline</li>
      <li><a href="">link-element</a></li>
      <li><a href="">link-element</a></li>
      <li><a href="">link-element</a></li>
      <li><a href="">link-element</a></li>
    </ul>
  </div>
  
  <div class="middle invisible">
    <ul>
      <li>Headline</li>
      <li><a href="">link-element</a></li>
      <li><a href="">link-element</a></li>
      <li><a href="">link-element</a></li>
      <li><a href="">link-element</a></li>
    </ul>
  </div>
  
  <div class="right invisible">
    <ul>
      <li>Administration</li>
      <?php
      
        if (!isset($_SESSION['angemeldet']) || !$_SESSION['angemeldet']) {
            echo '<li><a href="'.BASE.'admin/">Index</a></li>';
        } else {
//            echo '<li><a href="'.BASE.'admin/index.php">Index</a></li>';
//            echo '<li><a href="'.BASE.'admin/CREATOR.php">Setup</a></li>';
            echo '<li><a href="'.BASE.'admin/FILLER.php">Bef&uuml;llen</a></li>';
            echo '<li><a href="'.BASE.'admin/UPLOADER.php">Upload</a></li>';
            echo '<li><a href="'.BASE.'admin/delete.php">L&ouml;schen</a></li>';
            
        }
      
      ?>
      
      
      
      
    </ul>
  </div>
  
  <div class="clear"></div>
  
  <div id="bottom">
    <?php
    echo "·:::· ";
    
    if(SITE_LOADTIME == "1") 
    echo "Seiten-Ladezeit: <span id=\"Ladezeit\"><b class=\"warn\">no js</b></span> Sek. ·:::· ";
    
    if(VALIDATOR == "1") echo '
    <!-- html-validator -->
    <a href="http://validator.w3.org/check?uri=referer" target="_blank">
      <img style="margin-bottom:-4px;" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" title="Valid XHTML 1.0 Transitional" height="16" />
    </a> ·:::· ';
    
    ?>
    
  </div>
  
</footer>
