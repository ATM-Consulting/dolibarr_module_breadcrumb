<?php
class ActionsBreadcrumb
{
     /** Overloading the doActions function : replacing the parent's function with the one below
      *  @param      parameters  meta datas of the hook (context, etc...)
      *  @param      object             the object you want to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
      *  @param      action             current action (if set). Generally create or edit or null
      *  @return       void
      */

    function formObjectOptions($parameters, &$object, &$action, $hookmanager)
    {
	  	$this->updateCookie($object);
		return 0;
	}


	/**
	 *
	 * @param unknown $object
	 * @return boolean
	 */
	private function updateCookie(&$object) {
		if(!empty($_POST)) return false; // rien si post

		global $db;

        if(!defined('BREADCRUMB_ALREADY_IN_PAGE')) {

            define('BREADCRUMB_ALREADY_IN_PAGE', 1);

            dol_include_once('/breadcrumb/lib/breadcrumb.lib.php');

            $cookiename = getCookieName();

            if(isset($_COOKIE[$cookiename])) {
            $TCookie = json_decode( $_COOKIE[$cookiename] );
            }

            if(empty($TCookie)){
                $TCookie = array();
            }
            
            if(!BCactionInUrl($_SERVER['REQUEST_URI'])) {
                
                 $linkName = '';
                 $linkTooltip = '';
                 $item = getBreadcrumbItemInfoFromObject($object);
                 $TSessionToolTip =& $_SESSION[$cookiename];
                 
                 if(empty($item)){
                     // Fall back for old Dolibarr versions
                     $item = getBreadcrumbItemInfoFromUrl($_SERVER['REQUEST_URI']);
                 }
                 
                 if(!empty($item)){
                     $linkName = $item['linkName'];
                     
                     if(!empty($item['linkTooltip'])){
                         $linkTooltip = $item['linkTooltip'];
                         
                         // Tooltips are stored in session due to cookies size limit
                         $TSessionToolTip[breadcrumbCurrentUrl()] = $item['linkTooltip'];
                     }
                 }

                ?><script type="text/javascript" >
                    var titre = "<?php echo addslashes($linkName) ?>";
                    var TCookie = new Array;
                    var url = document.location.href;
                    var fullurl='';

                    <?php

                        foreach($TCookie as $row) {

                            $TToPush = array(
                                json_encode($row[0]),
                                json_encode($row[1]),
                                json_encode($row[2])
                            );
                            
                            if(!empty($row[0])) {
                                ?>
                                TCookie.push([<?php echo implode(',', $TToPush); ?>]);
                                <?php
                            }

                        }

                    ?>

                    if(titre!="") {
                        for(x in TCookie) {
                            if(TCookie[x][1]==url || TCookie[x][2]==titre) {
                                delete TCookie[x];
                            };
                        }

                        TCookie.push([titre, url, fullurl]);
                        $.cookie("<?php echo $cookiename?>",  JSON.stringify(TCookie) , { path: '/', expires: 1 });

                    }

                </script>
                <?php
            }
        }
	}
}