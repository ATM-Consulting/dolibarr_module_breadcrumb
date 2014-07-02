<?php

	if(!empty($_POST)) exit; // no arianne on submit form

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
	
?>

var len_to_remove = <?php echo $len_to_remove ?>;

$(document).ready(function() {

	var TCookie = new Array;

	$('body').prepend("<div class=\"breadCrumbHolder module\"><div id=\"breadCrumb\" class=\"breadCrumb module\"><ul></ul></div></div>");
	$('#breadCrumb ul').append("<li><a href=\"#\">Home</a></li>");

	<?php
	
		foreach($TCookie as $row) {
		
			?>
			$('#breadCrumb ul').append("<li><a href=\"<?php echo $row[1] ?>\"><?php echo $row[0] ?></a></li>");
			TCookie.push(["<?php echo $row[0] ?>", "<?php echo $row[1] ?>"]);
			<?php
			
		}
	
	?>

	$('#breadCrumb').jBreadCrumb({easing:'none'});
	
	var titre = document.title.substr(len_to_remove);
	var url = document.location.href;
	
	f_find = false;
	for(x in TCookie)  {
		if(TCookie[x][1]==url) f_find = true;
	}
	if(!f_find) {
		TCookie.push([titre, url]);
		$.cookie("breadcrumb",  JSON.stringify(TCookie) , { path: '/', expires: 1 });
	}	

})

	
