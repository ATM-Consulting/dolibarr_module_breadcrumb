<?php

	if(!empty($_POST)) exit; // no breadcrumb on submit form
  
	require('../config.php');
	
    dol_include_once('/breadcrumb/lib/breadcrumb.lib.php');
    
	$appli='Dolibarr';
	if (!empty($conf->global->MAIN_APPLICATION_TITLE)) $appli=$conf->global->MAIN_APPLICATION_TITLE;
	
	if (!empty($conf->global->BREADCRUMB_NB_ELEMENT)) $nb_element_to_show=$conf->global->BREADCRUMB_NB_ELEMENT;
	else $nb_element_to_show = 10;
	
    $cookiename = getCookieName();
	
	$len_to_remove = strlen($appli) + 3;
	
	if(isset($_COOKIE[$cookiename])) {
		$TCookie = json_decode( $_COOKIE[$cookiename] );	
	}
	
	if(empty($TCookie)){
		$TCookie = array();
	}

	if(count($TCookie)>$nb_element_to_show) {
		
		$TCookie = array_slice($TCookie, count($TCookie) - $nb_element_to_show );
		
	}
	
	$titre = '';$full='';
	$referer = $_SERVER['HTTP_REFERER'];
		
    ?>
        var referer = "<?php echo $referer ?>";
    <?php
        
	if(!empty($referer)) {
	    $titre = getTitreFromUrl($referer);
	}
    
    if(!BCactionInUrl($referer)) {
        if(!empty($conf->global->BREADCRUMB_ALLOW_UNKNOWM_ELEMENTS) && empty($titre)) {
            ?>
            var titre = document.title;
            var fullurl = '';
            <?php
        }
        elseif(!empty($titre)) {
           ?>
           var titre = "<?php echo addslashes($titre) ?>";
           var fullurl = "";
           <?php    
        }
        else
        {
            ?>
            var titre = "";
            var fullurl = "";
            <?php
        }
        
        
    }
    
?>
var len_to_remove = <?php echo $len_to_remove ?>;

$(document).ready(function() {

	var TCookie = new Array;

	$container = $('div#id-container').first(); 
	if($container.length == 0) {
          $container = $('body').first('div');
    }
	
	$container.before("<div style=\"clear:both;\"></div><div class=\"breadCrumbHolder module\"><div id=\"breadCrumb\" class=\"breadCrumb module\"><ul></ul></div></div><div style=\"clear:both;\"></div>");
	$('#breadCrumb ul').append("<li><a href=\"<?php echo dol_buildpath('/',1) ?>\">Home</a></li>");

	<?php
	
		foreach($TCookie as $row) {
		
			if(!empty($row[0])) {
				
				if(!empty($row[2])) $url = $row[2];
				else $url = "<a href=\"".addslashes($row[1])."\">".$row[0]."</a>";
			
				?>
				$('#breadCrumb ul').append("<li><?php echo addslashes($url) ?></li>");
				TCookie.push(["<?php echo addslashes($row[0]) ?>", "<?php echo addslashes($row[1]) ?>", "<?php echo addslashes($row[2]) ?>"]);
				<?php
				
				
			}
		}
	
	?>

	$('#breadCrumb').jBreadCrumb({previewWidth : 50, timeInitialCollapse : 0, minimumCompressionElements:50});
	
	//if(titre=="") titre = document.title.substr(len_to_remove);
	var url = document.location.href;
	
	if(titre!="") {
		for(x in TCookie) {
			if(TCookie[x][1]==url || TCookie[x][2]==titre) { 
				delete TCookie[x];	
			};
		}
		
		TCookie.push([titre, url, fullurl]);
		$.cookie("<?php echo $cookiename?>",  JSON.stringify(TCookie) , { path: '/', expires: 1 });
		
	} 
	
	
})
<?php
