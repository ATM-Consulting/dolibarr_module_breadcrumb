<?php

	if(!empty($_POST)) exit; // no arianne on submit form

	require('../config.php');
	
	$appli='Dolibarr';
    if (!empty($conf->global->MAIN_APPLICATION_TITLE)) $appli=$conf->global->MAIN_APPLICATION_TITLE;
	
	$len_to_remove = strlen($appli) + 3;
	
	if(isset($_COOKIE['breadcrumb'])) {
		$TCookie = json_decode( $_COOKIE['breadcrumb'] );	
	}
	
	if(empty($TCookie)){
		$TCookie = array();
	}
	
	if(count($TCookie)>10) {
		
		$TCookie = array_slice($TCookie, count($TCookie) - 10 );
		
	}
	
	$moreText = '';
	// TODO ajouter nomtier, projet etc
	if(isset($_REQUEST['id']))$moreText.=' '.$_REQUEST['id'];
	else if(isset($_REQUEST['socid']))$moreText.=' '.$_REQUEST['socid'];
	
	
?>

var len_to_remove = <?php echo $len_to_remove ?>;

$(document).ready(function() {

	var TCookie = new Array;
	var moreText = "<?php echo addslashes($moreText) ?>";

	$('#id-container').before("<div class=\"breadCrumbHolder module\"><div id=\"breadCrumb\" class=\"breadCrumb module\"><ul></ul></div></div>");
	$('#breadCrumb ul').append("<li><a href=\"<?php echo dol_buildpath('/',1) ?>\">Home</a></li>");

	<?php
	
		foreach($TCookie as $row) {
		
			if(!empty($row[0])) {
				
			
				?>
				$('#breadCrumb ul').append("<li><a href=\"<?php echo $row[1] ?>\"><?php echo $row[0] ?></a></li>");
				TCookie.push(["<?php echo $row[0] ?>", "<?php echo $row[1] ?>"]);
				<?php
				
				
			}
		}
	
	?>

	$('#breadCrumb').jBreadCrumb({previewWidth : 50});
	
	var titre = document.title.substr(len_to_remove)+moreText;
	var url = document.location.href;
	
	for(x in TCookie)  {
		if(TCookie[x][1]==url) { 
			delete TCookie[x];	
		};
	}
	
	TCookie.push([titre, url]);
	$.cookie("breadcrumb",  JSON.stringify(TCookie) , { path: '/', expires: 1 });
	
})

	
