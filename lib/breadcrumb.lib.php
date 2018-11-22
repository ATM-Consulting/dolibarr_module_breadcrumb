<?php
    
    dol_include_once('/core/lib/functions.lib.php');
    
function getCookieName() {
    
    return 'breadcrumb'.md5( dol_buildpath('/') );
    
}    
    
function getTitreFromUrl($referer) {

    $titre = '';
    $item = getBreadcrumbItemInfoFromUrl($referer);
    if(!empty($item)){
        $titre = $item['linkName'];
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
    

function getBreadcrumbItemInfoFromUrl($referer) {
    global $db, $langs;
    $id = _get_id_from_url($referer);
    
    $return = array();
    
    if($id>0) {
        
        if(strpos($referer, 'propal.php') || strpos($referer, 'propal/card.php')) {
            dol_include_once('/comm/propal/class/propal.class.php');
            
            $object=new Propal($db);
            $object->fetch($id);
            
            $return['linkName'] = $object->ref;
            $return['linkTooltip'] = getObjectThirdpartyForTooltip($object);
        }
        
        elseif(strpos($referer, '/supplier_proposal/card.php')) {
            dol_include_once('/supplier_proposal/class/supplier_proposal.class.php');
            
            $object=new SupplierProposal($db);
            $object->fetch($id);
            
            $return['linkName'] = $object->ref;
            $return['linkTooltip'] = getObjectThirdpartyForTooltip($object);
        }
        
        else if(strpos($referer, '/compta/facture.php') || strpos($referer, '/compta/facture/card.php')) {
            dol_include_once('/compta/facture/class/facture.class.php');
            
            $object=new Facture($db);
            $object->fetch($id);
            
            $return['linkName'] = $object->ref;
            $return['linkTooltip'] = getObjectThirdpartyForTooltip($object);
            
        }
        
        else if(strpos($referer, '/fourn/facture/card.php')) {
            dol_include_once('/fourn/class/fournisseur.facture.class.php');
            
            $object=new FactureFournisseur($db);
            $object->fetch($id);
            
            $return['linkName'] = $object->ref;
            $return['linkTooltip'] = getObjectThirdpartyForTooltip($object);
            
        }
        
        else if(strpos($referer, '/fourn/commande/fiche.php') || strpos($referer, '/fourn/commande/card.php')) {
            dol_include_once('/fourn/class/fournisseur.commande.class.php');
            
            $object=new CommandeFournisseur($db);
            $object->fetch($id);
            
            $return['linkName'] = $object->ref;
            $return['linkTooltip'] = getObjectThirdpartyForTooltip($object);
            
        }
        
        else if(strpos($referer, 'commande/fiche.php') || strpos($referer, 'commande/card.php')) {
            dol_include_once('/commande/class/commande.class.php');
            
            $object=new Commande($db);
            $object->fetch($id);
            
            $return['linkName'] = $object->ref;
            $return['linkTooltip'] = getObjectThirdpartyForTooltip($object);
            
        }
        else if(strpos($referer, 'contact/fiche.php') || strpos($referer, 'contact/card.php')) {
            dol_include_once('/contact/class/contact.class.php');
            
            $object=new Contact($db);
            $object->fetch($id);
            
            $return['linkName'] =$object->firstname.' '.$object->lastname;
            
        }
        
        else if(strpos($referer, "societe/soc.php") || strpos($referer, 'societe/card.php')) {
            dol_include_once('/societe/class/societe.class.php');
            $object=new Societe($db);
            $object->fetch($id);
            $return['linkName'] = $object->name;
        }
        
        else if(strpos($referer, 'comm/fiche.php') || strpos($referer, 'comm/card.php') ) {
            dol_include_once('/societe/class/societe.class.php');
            $object=new Societe($db);
            $object->fetch($id);
            $return['linkName'] = $object->name;
        }
        
        else if(strpos($referer, "fourn/fiche.php") || strpos($referer, "fourn/card.php")  ) {
            dol_include_once('/societe/class/societe.class.php');
            $object=new Societe($db);
            $object->fetch($id);
            $langs->load('suppliers');
            $return['linkName'] = $langs->trans('Supplier').' '.$object->name;
        }
        else if(strpos($referer, 'projet/fiche.php') || strpos($referer, 'projet/card.php') ) {
            dol_include_once('/projet/class/project.class.php');
            
            $object=new Project($db);
            $object->fetch($id);
            $return['linkName'] = $object->ref;
        }
        else if(strpos($referer, 'product/fiche.php') || strpos($referer, 'product/card.php')   ) {
            dol_include_once('/product/class/product.class.php');
            
            $object=new Product($db);
            $object->fetch($id);
            $return['linkName'] = $object->ref;
        }
        elseif(strpos($referer, 'contrat/card.php') ) {
            dol_include_once('/contrat/class/contrat.class.php');
            
            $object=new Contrat($db);
            $object->fetch($id);
            $return['linkName'] = $object->ref;
            $return['linkTooltip'] = getObjectThirdpartyForTooltip($object);
        }
        elseif(strpos($referer, 'compta/sociales/card.php')) {
            // ne fonctionne pas actuellement car manque un hook dans dolibarr  -> // $hookmanager->initHooks(array('globalcard'));
            dol_include_once('compta/sociales/class/chargesociales.class.php');
            $object=new ChargeSociales($db);
            $object->fetch($id);
            $return['linkName'] = $object->lib;
        }
        elseif(strpos($referer, 'compta/bank/card.php')) {
            dol_include_once('compta/bank/class/bank.class.php');
            $object=new account($db);
            $object->fetch($id);
            $return['linkName'] = $object->ref;
        }
        elseif(strpos($referer, 'fichinter/card.php')) {
            dol_include_once('fichinter/class/fichinter.class.php');
            $object=new Fichinter($db);
            $object->fetch($id);
            //$langs->load("interventions"); //$langs->trans('Intervention').' '.
            $return['linkName'] = $object->ref;
        }
        elseif(strpos($referer, 'projet/tasks/task.php')) {
            dol_include_once('projet/class/task.class.php');
            $object=new Task($db);
            $object->fetch($id);
            $return['linkName'] = $object->ref;
        }
        
        
        if(!empty($object) && method_exists($object, 'getNomUrl')) {
            
            $type_element = $object->element;
            if($type_element=='societe')$type_element='company';
            elseif($type_element=='facture' || $type_element=='invoice_supplier')$type_element='bill';
            elseif($type_element=='commande' || $type_element=='order_supplier')$type_element='order';
            elseif($type_element=='contrat')$type_element='contract';
            elseif($type_element=='fichinter')$type_element='intervention';
            elseif($type_element=='project_task')$type_element='projecttask';
            
            
            $return['linkName'] = img_object('', $type_element).(!empty($return['linkName'])?$return['linkName']:'');

            // get tooltip info from std getNomUrl
            $getNomUrl = getAttrFromDomElement($object->getNomUrl());
            if(!empty($getNomUrl) && !in_array($type_element, array('societe','company'))){
                $return['linkTooltip'] = (!empty($return['linkTooltip'])?$return['linkTooltip'].'<br/>':'').dol_html_entity_decode($getNomUrl[0],ENT_QUOTES);
            }
            
           
        }
        
    }
    
    return $return;
    
}

function getObjectThirdpartyForTooltip($object, $force_thirdparty_id=0)
{
    if(!is_object($object)){
        return '';
    }
    
    // no need to fetch_thirdparty if all ready fetched
    if(empty($object->thirdparty)){
        $object->fetch_thirdparty($force_thirdparty_id);
    }
    
    
    if(!empty($object->thirdparty)){
        return '<b>'.$object->thirdparty->name.'</b>';
    }
    else{
        return '';
    }
}


function getAttrFromDomElement($html, $targetElement = 'a' , $attr = 'title', $returnFirstOnly = 1 )
{
    //Create a new DOM document
    $dom = new DOMDocument;
    $return = array();
    //Parse the HTML. The @ is used to suppress any parsing errors
    //that will be thrown if the $html string isn't valid XHTML.
    @$dom->loadHTML($html);
    
    //Get all links. You could also use any other tag name here,
    //like 'img' or 'table', to extract other tags.
    $elems = $dom->getElementsByTagName($targetElement);
    
    if(!empty($elems))
    {
        //Iterate over the extracted links and display their URLs
        foreach ($elems as $elem){
            //Extract and show the "href" attribute.
            $return[] = $elem->getAttribute($attr);
            if($returnFirstOnly){
                break;
            }
        }
    }
    
    return $return;
}


