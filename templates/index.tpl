<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>DTS{if $page_title}-{$page_title}{/if}</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="all"/>
		<script type="text/javascript" src="js/events.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/base.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular.min.js"></script>
	</head>
	<body>
		<div style="width:90%;margin-left:auto;margin-right:auto" class="">
			<div>
				<!-- menu start -->
				{$menu}
				<!-- menu end -->
			</div>
			<div id="feedback"></div>
			<div>
				<!-- page start -->
				{$page}
				<!-- page end -->
			</div>
		</div>
	</body>
</html>