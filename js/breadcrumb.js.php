<?php

	header('Content-Type: application/javascript');

	if(!empty($_POST)) exit; // no breadcrumb on submit form

	require('../config.php');

	$referer = $_SERVER['HTTP_REFERER'];
	if (strpos($referer, 'optioncss=print') !== false ) {
		exit;
	}

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
	$linkTooltip = '';


    ?>
        var referer = "<?php echo $referer ?>";
    <?php

	if(!empty($referer)) {
	    $item = getBreadcrumbItemInfoFromUrl($_SERVER['REQUEST_URI']);
	    if(!empty($item)){
	        $titre = $item['linkName'];
	        
	        if(!empty($item['linkTooltip'])){
	            $linkTooltip = $item['linkTooltip'];
	        }
	    }
	}
    ?>
    var titre = "";
    var fullurl = "";
    var linktooltip = "";
    <?php

    if(!BCactionInUrl($referer)) {
        if(!empty($conf->global->BREADCRUMB_ALLOW_UNKNOWM_ELEMENTS) && empty($titre)) {
            ?>
            titre = document.title;
            fullurl = '';
            linktooltip = '';
            <?php
        }
        elseif(!empty($titre)) {
           ?>
           titre = "<?php echo addslashes($titre) ?>";
           fullurl = "";
           linktooltip = <?php echo json_encode(str_replace(array("\n", "\r"), '',  $linkTooltip)); ?>;
           <?php
        }


    }

?>
var len_to_remove = <?php echo $len_to_remove ?>;

$(document).ready(function() {

	var TCookie = new Array;

<?php if($conf->theme == 'md') { ?>
	$container = $('div#id-right').children().first();
<?php } else { ?>
	$container = $('div#id-container').first();
<?php } ?>
	if($container.length == 0) {
          $container = $('body').first('div');
    }

	$container.before("<div style=\"clear:both;\"></div><div class=\"breadCrumbHolder module\"><div id=\"breadCrumb\" class=\"breadCrumb module\"><ul></ul></div></div><div style=\"clear:both;\"></div>");
<?php if($conf->theme == 'md') { ?>
	$('.breadCrumbHolder').addClass('md');
<?php } ?>

	$('#breadCrumb ul').append("<li><a href=\"<?php echo dol_buildpath('/',1) ?>\">Home</a></li>");

	<?php

		foreach($TCookie as $row) {

			if(!empty($row[0])) {

			    $toolTipAttr = '';
			    if(!empty($row[3])){
			        $toolTipAttr = ' class="breadcrumbTooltip" title="'.dol_escape_htmltag($row[3], 1).'" ';
			    }
			    else{
			        $row[3] = '';
			    }
			    
			    
			    if(!empty($row[2])){
			        $url = $row[2];
			        if(!empty($row[3])){
			            $url = '<span '.$toolTipAttr.' >'.$row[0].'</a>';
			        }
			    }
			    else{
			        $url = '<a '.$toolTipAttr.' href="'.addslashes($row[1]).'">'.$row[0].'</a>';
			    }

				?>
				$('#breadCrumb ul').append("<li><?php echo addslashes($url) ?></li>");
				TCookie.push([<?php echo json_encode($row[0]) ?>, <?php echo json_encode($row[1]) ?>, <?php echo json_encode($row[2]) ?>, <?php echo json_encode($row[3]) ?>]);
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

		TCookie.push([titre, url, fullurl, linktooltip]);
		$.cookie("<?php echo $cookiename?>",  JSON.stringify(TCookie) , { path: '/', expires: 1 });

	}


	var breadcrumbtooltip = $('.breadcrumbTooltip');
	// add tooltip
	breadcrumbtooltip.tooltip({
		show: { collision: "flipfit", effect:"toggle", delay:50 },
		hide: { delay: 50 },
		tooltipClass: "mytooltip",
		content: function () {
			return $(this).prop("title");		/* To force to get title as is */
		}
	});


})
<?php
