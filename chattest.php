<?php

include('includes/breadcrumb.php'); 
require_once"includes/app.php";
require_once("includes/global.php");
require_once("includes/auth.php");

define('SMALL_VIEW', "sml_view");

//==========Process============
$c ='';
if(isset($_GET['r']))
{
	header("Location: $_GET[r]");
}elseif(isset($_REQUEST[SMALL_VIEW])){

	if(substr(safe_get($_REQUEST['page']), 0, 4) == 'http')
	{
		$page = "default.php";
	}else{
		if(substr($_REQUEST['page'], 0, 4) == 'http')
		{
			$page = "default.php";
		}else{
		if(isset($_REQUEST['page']))
			{
				$page = $_REQUEST['page'].'.php';
			}else{
				$page = "default.php";
			}
		}
	}
	$c .='<html>
	<head>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
		<script src="js/events.js" type="text/javascript"></script>
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/base.js" type="text/javascript"></script>
	</head>
	<body>';
	$c .= ob_get_output("pages/$page");
	$c .= '</body>
</html>';
}else{
	if(substr(safe_get($_REQUEST['page']), 0, 7) == 'http://')
	{
		$page = "load.php";
	}else{
		if(isset($_REQUEST['page']))
		{
			$page = $_REQUEST['page'].'.php';
		}else{
			$page = "load.php";
		}
	}
	
$page_content = ob_get_output("pages/$page");

	//$c.= '<!DOCTYPE HTML PUBLIC "ISO/IEC 15445:1999//DTD HTML//EN">';
	$c.= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
$c .= '<html>
	<head>';
		isset($GLOBALS['page_title']) ? $c .= '<title>DTS-'.$GLOBALS['page_title'].'</title>' : '';
$c .= '	<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
		<script src="js/events.js" type="text/javascript"></script>
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/base.js" type="text/javascript"></script>
	</head>
	<body>';
$c .= '	<center>
		<table width="90%">
			<tr>
				<td valign="top" width="1%">
					'.ob_get_output("includes/dts_menu.php").'
					
				</td>
			</tr>
			<tr>
				<td valign="top">
					
					'.$page_content.'
				</td>
			</tr>
		</table>
		</center>
		';
		//if(logged_in_as('admin'))
		//{
			$c .= ob_get_output(App::$temp."/overdue_load_mod.tpl");
			$c .= ob_get_output(App::$temp."/chat.tpl");
		//}
$c .= '
	</body>
</html>';

}
header("Cache-Control: no-cache, must-revalidate");
echo $c;
?>