
<header>
  <h1 id="headline"><a href="<?= BASE . 'admin/index.php' ?>"><?= SITE_NAME ?></a></h1>
  <ul id="headnav">
  	
    <li><a href="index.php"><img src="<?= BASE ?>inc/img/icons/folder.png" alt="" />usable</a></li>
    <li><a href="unusable.php"><img src="<?= BASE ?>inc/img/icons/delete.png" alt="" />unusable</a></li>
    <li><a href="delete.php"><img src="<?= BASE ?>inc/img/icons/remove.png" alt="" />delete</a></li>
    <li><a href="upload.php"><img src="<?= BASE ?>inc/img/icons/add.png" alt="" />upload</a></li>
    <li><a href="logout.php"><img src="<?= BASE ?>inc/img/icons/hex_delete.png" alt="" />logout</a></li>
  </ul>
  
  <script>
		var path = window.location.pathname;
		var pathArr = path.split("/");
		var loc = pathArr[pathArr.length - 1];

		$('#headnav').find('a').each(function() {
			$(this).toggleClass('active', $(this).attr('href') == loc);
		});
	</script>

  <div class="clear"></div>
  
</header>









