<link rel="stylesheet" type="text/css" href="style.css">

<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-1.12.4.js"></script>
<script src="js/jquery-ui.js"></script>

<!-- choose a theme file -->
<link rel="stylesheet" href="css/theme.blue.css">
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>


<!-- tablesorter widgets (optional) -->
<script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>

<!-- tablesorter.pager -->
<link rel="stylesheet" href="css/jquery.tablesorter.pager.min.css">
<script type="text/javascript" src="js/jquery.tablesorter.pager.min.js"></script>

<script>
$(function() {
	$("#myTable").tablesorter({
		widgets: ["zebra", "filter"]
	});//.tablesorterPager({container: $("#pager")}); ;
	$('#departement').select2();
	$('.datepicker_air').datepicker({		dateFormat: "yyyy.mm.dd",
		timepicker: true	})
});
</script>

<!-- Select2 -->
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Air Datepicker -->
<link href="css/datepicker.min.css" rel="stylesheet" type="text/css">
<script src="js/datepicker.min.js"></script>