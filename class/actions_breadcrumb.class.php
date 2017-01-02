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

	private function updateCookie(&$object) {
		if(!empty($_POST)) return false; // rien si post

		$cssprint=GETPOST('optioncss');

		global $db;

        if(!defined('BREADCRUMB_ALREADY_IN_PAGE') && empty($cssprint)) {

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
                 $titre = getTitreFromUrl($_SERVER['REQUEST_URI']);

                ?><script type="text/javascript">
                    var titre = "<?php echo addslashes($titre) ?>";
                    var TCookie = new Array;

                    var url = document.location.href;
                    var fullurl='';

                    <?php

                        foreach($TCookie as $row) {

                            if(!empty($row[0])) {
                                ?>
                                TCookie.push(["<?php echo addslashes($row[0]) ?>", "<?php echo $row[1] ?>", "<?php echo addslashes($row[2]) ?>"]);
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