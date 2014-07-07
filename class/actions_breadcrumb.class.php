<?php
class ActionsBreadcrumb
{ 
     /** Overloading the doActions function : replacing the parent's function with the one below 
      *  @param      parameters  meta datas of the hook (context, etc...) 
      *  @param      object             the object you want to process (an invoice if you are in invoice module, a propale in propale's module, etc...) 
      *  @param      action             current action (if set). Generally create or edit or null 
      *  @return       void 
      */
    
    function doActions($parameters, &$object, &$action, $hookmanager) 
    {
    	$this->updateCookie($object);
		return 0;
	}
    
    
    
      
    function formObjectOptions($parameters, &$object, &$action, $hookmanager) 
    { 
      
	  	$this->updateCookie($object);
		return 0;
	}
	
	private function updateCookie(&$object) {
		/*if(!empty($_POST)) return false; // rien si post

		if(isset($_COOKIE['breadcrumb'])) {
			$TCookie = json_decode( $_COOKIE['breadcrumb'] );	
		}
		
		if(empty($TCookie)){
			$TCookie = array();
		}
		
		$url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";*/
		/*
		?><script type="text/javascript">
			var TCookie = new Array;
			
		<?php
			
		foreach($TCookie as &$cookie) {
			
			if(trim($cookie[1])==trim($url)) {
			
				if(!empty($object->ref)) {
					$cookie[0] = $object->ref;
				}	
				
			}
			if(!empty($cookie[0]) && !empty($cookie[1])) {
				?>TCookie.push(["<?php echo $cookie[0] ?>", "<?php echo $cookie[1] ?>"]);<?php	
			}
		}
			
		?>
		$.cookie("breadcrumb",  JSON.stringify(TCookie) , { path: '/', expires: 1 });
		
		</script>
		<?php
		*/
	}
	
}