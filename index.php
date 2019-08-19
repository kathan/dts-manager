<?php

require_once"includes/app.php";
require_once('includes/Template.php'); 

require_once("includes/global.php");
define('SMALL_VIEW', "sml_view");
//include_once("includes/hit.php");
$t = new Template();
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
		if(isset($_REQUEST['page']) && file_exists("pages/$page"))
			{
				$page = $_REQUEST['page'].'.php';
			}else{
				$page = "default.php";
			}
		}
	}
	$page = ob_get_output("pages/$page");
	$c .='<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="http://'.HTTP_ROOT.'/style.css" type="text/css" media="all">
		<script src="http://'.HTTP_ROOT.'/events.js.php" type="text/javascript"></script>
		<script src="jquery.js" type="text/javascript"></script>
		<script type="text/javascript" src="scripts/jquery.ui.js"></script>
		<script type="text/javascript" src="scripts/jquery.datepick.js"></script>
		<script src="http://'.HTTP_ROOT.'/base.js" type="text/javascript"></script>
		<title>'.$GLOBALS['page_title'].'</title>
	</head>
	<body>';
	
	$c .= '</body>
</html>';

	$t->assign('page', $page);
	$t->assign('page_title', $GLOBALS['page_title']);
	$c = $t->fetch(App::getTempDir().'index_sml.tpl');
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
$c .= '	<link rel="stylesheet" href="http://'.HTTP_ROOT.'/style.css" type="text/css" media="all">
		<script src="http://'.HTTP_ROOT.'/events.js.php" type="text/javascript"></script>
		<script src="jquery.js" type="text/javascript"></script>
		<script type="text/javascript" src="scripts/jquery.ui.js"></script>
		<script type="text/javascript" src="scripts/jquery.datepick.js"></script>
		<script src="http://'.HTTP_ROOT.'/base.js" type="text/javascript"></script>
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
			$c .= ob_get_output(App::getTempDir()."/overdue_load_mod.tpl");
			//$c .= ob_get_output(App::$temp."/chat.tpl");
		//}
$c .= '
	</body>
</html>';

$t = new Template();
$t->assign('menu', ob_get_output("includes/dts_menu.php"));
$t->assign('overdue', ob_get_output(App::getTempDir()."/overdue_load_mod.tpl"));
$t->assign('page', $page_content);
$c2 = $t->fetch(App::getTempDir() .'index.tpl');
}
header("Cache-Control: no-cache, must-revalidate");
echo $c;
?>