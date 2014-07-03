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
	
	$titre = '';
	$referer = $_SERVER['HTTP_REFERER'];
		
	if(!empty($referer)) {
	
		$id = _get_id_from_url($referer);
		
		if($id>0) {
			if(strpos($referer, "propal.php")) {
				dol_include_once('/comm/propal/class/propal.class.php');
				
				$object=new Propal($db);
				$object->fetch($id);
				
				$titre = $object->ref;
				
			}
			else if(strpos($referer, "facture.php")) {
				dol_include_once('/compta/facture/class/facture.class.php');
				
				$object=new Facture($db);
				$object->fetch($id);
				
				$titre = $object->ref;
				
			}

			else if(strpos($referer, "/fourn/commande/fiche.php")) {
				dol_include_once('/fourn/class/fournisseur.commande.class.php');
				
				$object=new CommandeFournisseur($db);
				$object->fetch($id);
				
				$titre = $object->ref;
				
			}

			else if(strpos($referer, "commande/fiche.php")) {
				dol_include_once('/commande/class/commande.class.php');
				
				$object=new Commande($db);
				$object->fetch($id);
				
				$titre = $object->ref;
				
			}
			else if(strpos($referer, "contact/fiche.php")) {
				dol_include_once('/contact/class/contact.class.php');
				
				$object=new Contact($db);
				$object->fetch($id);
				
				$titre = $langs->trans('Contact').' '.$object->firstname.' '.$object->lastname;
				
			}
			
			else if(strpos($referer, "societe/soc.php")  ) {
				dol_include_once('/societe/class/societe.class.php');
				
				$object=new Societe($db);
				$object->fetch($id);
				
				$titre = $object->name;
			}
			
			else if(strpos($referer, "comm/fiche.php")  ) {
				dol_include_once('/societe/class/societe.class.php');
				
				$object=new Societe($db);
				$object->fetch($id);
				
				$titre = $langs->trans('Customer').' '.$object->name;
			}
			
			else if(strpos($referer, "fourn/fiche.php")  ) {
				dol_include_once('/societe/class/societe.class.php');
				
				$object=new Societe($db);
				$object->fetch($id);
				
				$langs->load('suppliers');
				
				$titre = $langs->trans('Supplier').' '.$object->name;
			}
			
		}
		
		
	}
	
	
?>

var len_to_remove = <?php echo $len_to_remove ?>;

$(document).ready(function() {

	var TCookie = new Array;
	var titre = "<?php echo addslashes($titre) ?>";

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
	
	//if(titre=="") titre = document.title.substr(len_to_remove);
	var url = document.location.href;
	
	if(titre!="") {
		for(x in TCookie)  {
			if(TCookie[x][1]==url || TCookie[x][2]==titre) { 
				delete TCookie[x];	
			};
		}
		
		TCookie.push([titre, url]);
		$.cookie("breadcrumb",  JSON.stringify(TCookie) , { path: '/', expires: 1 });
		
	} 
	
	
})
<?php

function _get_id_from_url($url) {
	
	$pos = strpos($url, 'id=');
	
	if($pos!==false) {
		
		$id = (int)substr($url, $pos+3, 10);
		return $id;
	}
	
	return -1;
}
	
