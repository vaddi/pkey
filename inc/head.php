<head>
  <title><?= SITE_NAME ?></title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <!-- metadata -->
  <meta name="keywords" content="<?= SITE_KEYWORDS; ?>" />
  <meta name="description" content="<?= SITE_DESCRIPTION; ?>" />
  <meta name="publisher" content="<?= SITE_PUBLISHER; ?>" />
  <!-- icon -->
  <link rel="shortcut icon" href="<?= BASE; ?>inc/img/icons/attention.png" />
  <!-- styles -->
  <link type="text/css" rel="stylesheet" href="<?= BASE; ?>inc/css/style.css" />
  <style type="text/css" title="currentStyle">
	@import "../inc/css/demo_table.css";
  </style>
  <!--javascript-->
  <script type="text/javascript" src="<?= BASE; ?>inc/js/jquery.js"></script>
  <script type="text/javascript" src="<?= BASE; ?>inc/js/ladezeit.js"></script>
  <script type="text/javascript" src="<?= BASE; ?>inc/js/jquery.dataTables.js"></script>
  <script type="text/javascript">
	$(document).ready(function() {
		$('#example').dataTable( {
		    "sPaginationType": "full_numbers",
		    "aLengthMenu": [['-1', 10, 25, 50], ['Alle', 10, 25, 50]],
		    "bStateSave": true,
		    
		    "oLanguage": {
			    "sLengthMenu": "Zeige _MENU_ Eintr&auml;ge pro Seite",
			    "sSearch": "Suche:",
			    "sZeroRecords": "Keine Treffer in der Suchanfrage!",
			    "sInfo": "Zeige _START_ bis _END_ von _TOTAL_ Eintr&auml;gen",
			    "sInfoEmpty": "Zeige 0 bis 0 von 0 Eintr&auml;gen",
			    "sInfoFiltered": "(Gefiltert aus _MAX_ Eintr&auml;gen)",
			    "oPaginate": {
	    		    "sFirst": "Erste",
	    		    "sLast": "Letzte",
	    		    "sNext": "N&auml;chste",
    			    "sPrevious": "Vorherige"
			    }
		    }
	    } );
	} );
	
	function selectAllCheckBoxes(obj){
    $('input:checkbox:not(:disabled).checkBoxInLoop').prop('checked', jQuery(obj).prop('checked'));
    }

    function unselectCheckBoxUnik(){

        /*first you verify if the quantity of checkboxes equals number of checkboxes*/
        a = $('input:checkbox:not(:disabled).checkBoxInLoop:checked').size();
        b = $('input:checkbox:not(:disabled).checkBoxInLoop').size();

        /*if equals, mark a checkBoxUnik*/
        ((a == b)? $('#checkBoxUnik').prop("checked", true) : $('#checkBoxUnik').prop("checked", false));
    }
    
  </script>
  <!-- viewport for scalebale devices -->
  <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
</head>

