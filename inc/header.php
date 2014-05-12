<header>
  <h1 id="headline"><a href="<?= BASE . 'admin/index.php' ?>"><?= SITE_NAME ?></a></h1>
  <ul id="headnav">
    <li><a href="index.php">Main</a></li>
    <li><a href="about.php">About</a></li>
    <li><a href="admin/logout.php">Logout</a></li>
  </ul>
  
  <div class="clear"></div>
  
  <script>
		var path = window.location.pathname;
		var pathArr = path.split("/");
		var loc = pathArr[pathArr.length - 1];

		$('#headnav').find('a').each(function() {
			$(this).toggleClass('active', $(this).attr('href') == loc);
		});
	</script>
</header>
