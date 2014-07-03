<?php

		
	if(is_file('../main.inc.php'))$dir = '../';
	else  if(is_file('../../../main.inc.php'))$dir = '../../../';
	else $dir = '../../';

	include($dir."main.inc.php");

	dol_include_once('/core/lib/functions.lib.php');