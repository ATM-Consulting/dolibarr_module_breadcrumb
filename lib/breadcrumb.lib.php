<?php
    
    dol_include_once('/core/lib/functions.lib.php');
    
function getCookieName() {
    
    return 'breadcrumb'.md5( dol_buildpath('/') );
    
}    
    
function getTitreFromUrl($referer) {
global $db, $langs;
        $id = _get_id_from_url($referer);
        
        if($id>0) {
            if(strpos($referer, 'propal.php')) {
                dol_include_once('/comm/propal/class/propal.class.php');
                
                $object=new Propal($db);
                $object->fetch($id);
                
                $titre = $object->ref;
            }
            else if(strpos($referer, 'facture.php')) {
                dol_include_once('/compta/facture/class/facture.class.php');
                
                $object=new Facture($db);
                $object->fetch($id);
                
                $titre = $object->ref;
                
            }

            else if(strpos($referer, '/fourn/commande/fiche.php') || strpos($referer, '/fourn/commande/card.php')) {
                dol_include_once('/fourn/class/fournisseur.commande.class.php');
                
                $object=new CommandeFournisseur($db);
                $object->fetch($id);
                
                $titre = $object->ref;
                
            }

            else if(strpos($referer, 'commande/fiche.php') || strpos($referer, 'commande/card.php')) {
                dol_include_once('/commande/class/commande.class.php');
                
                $object=new Commande($db);
                $object->fetch($id);
                
                $titre = $object->ref;
                
            }
            else if(strpos($referer, 'contact/fiche.php') || strpos($referer, 'contact/card.php')) {
                dol_include_once('/contact/class/contact.class.php');
                
                $object=new Contact($db);
                $object->fetch($id);
                
                $titre =$object->firstname.' '.$object->lastname;
                
            }
            
            else if(strpos($referer, "societe/soc.php")  ) {
                dol_include_once('/societe/class/societe.class.php');
                
                $object=new Societe($db);
                $object->fetch($id);
                
                $titre = $object->name;
            }
            
            else if(strpos($referer, 'comm/fiche.php') || strpos($referer, 'comm/card.php') ) {
                dol_include_once('/societe/class/societe.class.php');
                
                $object=new Societe($db);
                $object->fetch($id);
                
                $titre = $object->name;
            }
            
            else if(strpos($referer, "fourn/fiche.php") || strpos($referer, "fourn/card.php")  ) {
                dol_include_once('/societe/class/societe.class.php');
                
                $object=new Societe($db);
                $object->fetch($id);
                
                $langs->load('suppliers');
                
                $titre = $langs->trans('Supplier').' '.$object->name;
            }
            else if(strpos($referer, 'projet/fiche.php') || strpos($referer, 'projet/card.php') ) {
                dol_include_once('/projet/class/project.class.php');
                
                $object=new Project($db);
                $object->fetch($id);
                
                $titre = $object->ref;
            }
            else if(strpos($referer, 'product/fiche.php') || strpos($referer, 'product/card.php')   ) {
                dol_include_once('/product/class/product.class.php');
                
                $object=new Product($db);
                $object->fetch($id);
                
                $titre = $object->ref;
            }


            if(!empty($object) && method_exists($object, 'getNomUrl')) {
            
                $type_element = $object->element;
                if($type_element=='societe')$type_element='company';
                elseif($type_element=='facture')$type_element='bill';
                
                
                $titre = img_object('', $type_element).$titre;
            }
            
        }

    return $titre;

}

function BCactionInUrl($url) {
    
    if(strpos($url,'action=')!==false && strpos($url,'action=list')===false ) {
        return true;
    }
    else{
        return false;
    }
    
}

function _get_id_from_url($url) {
    
    $pos = strpos($url, 'id=');
    
    if($pos!==false) {
        
        $id = (int)substr($url, $pos+3, 10);
        return $id;
    }
    
    return -1;
}
    