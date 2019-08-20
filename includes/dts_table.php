<?php
require_once"global.php";
require_once"auth.php";
require_once"table.php";
require_once"tab_menu.php";
class dts_table extends table{
	var $delete_icon='<img border=0 src="images/delete.gif" />';
	var $search = 'Search';
	var $edit = 'edit';
	var $load_carrier = 'load_carrier';
	var $customer = 'customer';
	var $carrier = 'carrier';
	var $search_edit = 'search_edit';
	var $all='all';
	var $update = 'update';
	var $portal ='portal';
	var $account_status_list = Array('Not Active' => 'Not Active', 'Active' => 'Active', 'Accounting Hold' => 'Accounting Hold');
	public static $load_type = Array(''=>'', 'TL'=>'TL', 'LTL'=>'LTL', 'RAIL'=>'RAIL', 'OCEAN'=>'OCEAN', 'REFRIGERATED'=>'REFRIGERATED','PARTIAL'=>'PARTIAL');
	var $trailer_type_list = Array('Van' => 'Van', 'Refrig' => 'Refrig', 'Flatbed' => 'Flatbed');
	var $rating_list = Array('Standard' => 'Standard', 'Expedited' => 'Expedited');
	var $load_warehouse ='load_warehouse';
	var $load ='load';
	var $delete ='delete';
	var $print ='print';
	var $warehouse = 'warehouse';
	var $confirm = 'confirm';
	var $customer_notes = 'customer_notes';
	var $cust_to_wh = 'cust_to_wh';
	var $wh_to_cust = 'wh_to_cust';
	var $action = 'action';
	var $php_date_format = 'm/d/Y';
	var $php_time_format = 'h:i A';
	var $db_date_format = '%Y-%m-%e';
	var $db_time_format = '%H:%i:%s';
	var $date_format = '%m/%e/%Y';
	var $null_date ='0000-00-00';
	var $blank_date ='00/00/0000';
	var $null_time ='00:00:00';
	var $time_format = '%l:%i %p';
	var $current_row;
	var $cancel_color='red';
	var $cancel_style ='color:white;background-color:red;padding:1px';
	var $expedited_style='color:black;background-color:orange;padding:1px';
	var $expedited_color='orange';
	var $content_color='RoyalBlue';
	var $ltl_carriers = Array('','AAA Cooper'=>'AAA Cooper','A Duie Pyle'=>'A Duie Pyle','Brandt'=>'Brandt','Central Freight'=>'Central Freight','FedEx'=>'FedEx','Jevic'=>'Jevic','Land Air Express '=>'Land Air Express ', 'Milan Express'=>'Milan Express','New Century'=>'New Century','New Penn'=>'New Penn','Oak Harbor'=>'Oak Harbor','Pitt Ohio'=>'Pitt Ohio','R&L Carriers'=>'R&L Carriers','Roadrunner Dawes'=>'Roadrunner Dawes','SAIA'=>'SAIA', 'Vitran Express'=>'Vitran Express', 'Yellow'=>'Yellow', 'USF Holland'=>'USF Holland', 'Dohrn'=>'Dohrn', 'Nebraska Transfer'=>'Nebraska Transfer', 'Southeastern Freight' => 'Southeastern Freight', 'Lakeville Motors'=>'Lakeville Motors', 'Midwest Motor Exp'=>'Midwest Motor Exp','N&M Transfer'=>'N&M Transfer', 'Dayton Freight'=>'Dayton Freight', 'Standard Forwarding'=>'Standard Forwarding', 'Conway'=>'Conway', 'Volunteer Express'=>'Volunteer Express', 'Southwestern Motors'=>'Southwestern Motors','USF Reddaway'=>'USF Reddaway', 'Ward Trucking'=>'Ward Trucking', 'Frontline Freight'=>'Frontline Freight', 'US Special Delivery'=>'US Special Delivery', 'Central Transport'=>'Central Transport');
	var $times = [];
	var $states = Array("AL" => "AL", "AK" => "AK", "AZ" => "AZ", "AR" => "AR", "CA" => "CA", "CO" => "CO", "CT" => "CT", "DE" => "DE", "DC" => "DC", "FL" => "FL", "GA" => "GA", "HI" => "HI", "ID" => "ID", "IL" => "IL", "IN" => "IN", "IA" => "IA", "KS" => "KS", "KY" => "KY", "LA" => "LA", "ME" => "ME", "MD" => "MD", "MA" => "MA", "MI" => "MI", "MN" => "MN", "MS" => "MS", "MO" => "MO", "MT" => "MT", "NE" => "NE", "NV" => "NV", "NH" => "NH", "NJ" => "NJ", "NM" => "NM", "NY" => "NY", "NC" => "NC", "ND" => "ND", "OH" => "OH", "OK" => "OK", "OR" => "OR", "PA" => "PA", "RI" => "RI", "SC" => "SC", "SD" => "SD", "TN" => "TN", "TX" => "TX", "UT" => "UT", "VT" => "VT", "VA" => "VA", "WA" => "WA", "WV" => "WV", "WI" => "WI", "WY" => "WY");
	var $load_classes = Array(50=>50, 55=>55, 60=>60, 65=>65, 70=>70, '77.5'=>77.5, 85=>85, '92.5'=>92.5, 100=>100, 110=>110, 125=>125, 150=>150, 175=>175, 200=>200, 250=>250, 300=>300, 400=>400, 500=>500, 'More'=>'More');
	var $breadcrumb;
	var $warehouse_types = Array('PICK'=>'PICK', 'DEST'=>'DEST');
	var $page = 'page';
	var $tab_menu;
	var $prefix;
	var $null_str ='<span style="color:red">N/A</span>';
	
	function __construct($name, $desc=true){
		asort($this->ltl_carriers);
		$this->generate_times();
		parent::__construct($name, $desc);
		$this->add_to_breadcrumb($this->page, $name);
		$this->tab_menu = new tab_menu();
	}
	function money($num){
		return number_format($num, 2, '.', '');
	}
	
        function generate_times(){
	$this->times['null'] ='';
	$start_time = 0;
	$end_time = 24;
	$interval = 15;//minutes
	$am_pm = 'AM';
	
	for($h = $start_time; $h <= $end_time; $h++)
	{
		for($m = 0; $m < 60; $m += $interval)
		{
			$time = date('g:i A', strtotime($h.':'.$m));
			$this->times[$time] = $time;
		}
	}
	//return $times;
}
	function generate_times_old()
	{
		$start_time = 0;
		$end_time = 24;
		$interval = 15;//minutes
		$am_pm = 'AM';
		
		for($h = $start_time; $h <= $end_time; $h++)
		{
			
			for($m = 0; $m < 60; $m += $interval)
			{
				if($h >= 12)
				{
					$am_pm = 'PM';
				}
				if($h > 12)
				{
					$time = ($h-12).":".str_pad($m, 2, '0')." $am_pm";
				}else{
					$time = "$h:".str_pad($m, 2, '0')." $am_pm";
				}
				//echo $time;
				$this->times[$time] = $time;
			}
		}
		$m=0;
		if($h >= 12)
		{
			$am_pm = 'PM';
		}
		if($h > 12)
		{
			$time = ($h-12).":".str_pad($m, 2, '0')." $am_pm";
		}else{
			$time = "$h:".str_pad($m, 2, '0')." $am_pm";
		}
		$this->times[$time] = $time;
	}
	function get_users()
	{
		$sql = "SELECT user_id, IF(active = 1, username, concat(substr(first_name, 1, 1), substr(last_name, 1, 1))) username FROM users order by active desc";
		$re = DB::query($sql);
		$result = Array('');
		while($r = DB::fetch_assoc($re))
		{
			$result[$r['user_id']] = $r['username'];
		}
		return $result;
	}
	function nbsp($str)
	{
		return str_replace(' ', '&nbsp;', $str);
	}
	
	
	function add_to_breadcrumb($name, $value=null)
	{
		if(isset($this->breadcrumb))
		{
			$this->breadcrumb .= "&";
		}
		$this->breadcrumb .= $name.'='.safe_get($value);
	}
	
	function back_button()
	{
		if(isset($_SERVER['HTTP_REFERER']))
		{
			return "</tr><tr><td><div class='menu' onclick='history.back();'>&lt;&lt;&nbsp;Back</div></td>";
		}
	}
	
	function get_tab_menu()
	{
		return $this->tab_menu->render();
	}
	function db_script()
	{
		return "<script src='db_save.js.php' type=\"text/javascript\"></script>";
	}
	function sortable_script()
	{
		//return "<script src='sortable.js'></script>";
	}
	function check_size()
	{
		return $this->script("window.onresize = resize;

function resize()
{
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  //window.status = 'Width = ' + myWidth + ' Height = ' + myHeight;
  window.alert( 'Width = ' + myWidth + ' Height = ' + myHeight );
}");
	}
	function popup_script()
	{
		return $this->script("			
				function popUp(URL, id, width, height)
				{
					if(!width)
					{
						width=600;
					}
					if(!height)
					{
						height = 600;
					}
					if(!id)
					{
						day = new Date();
						id = day.getTime();
					}
					eval(\"page\" + id + \" = window.open(URL, '\" + id + \"', 'toolbar=0,location=0,statusbar=0,menubar=0,resizable=1,width=\"+width+\",height=\"+height+\",left = 0,top = 0');\");
				}");
	}
	function portal_script()
	{
		return $this->script("
			function get_portal(table, params)
			{
				try
				{
  				var d = document.getElementById(table+'_portal');
				  d.innerHTML = 'Loading '+table;			
					var url = '?page=$this->name&portal='+table+'&action=portal&".SMALL_VIEW."&'+params;
					var portal = getFromURL(url);
				}catch(e)
				{
					alert('Error in get_portal:'+e.description + ' url:' + url);
				}
				d.innerHTML = '';
				d.innerHTML = portal;
				
			}");
			
	}
	function module_script()
	{
		return $this->script("
			function get_module(name, params)
			{
				var d = document.getElementById(name+'_module');
				//alert(table+'1');
				d.innerHTML = 'Loading '+name;
				//alert(table+'2');
				try
					{
					var url = 'http://".HTTP_ROOT."/?page=$this->page&module='+name+'&".SMALL_VIEW."&'+params;
					//var url = 'http://".HTTP_ROOT."/?page=$this->page&module='+name+'&action=module&".SMALL_VIEW."&'+params;
				var module = getFromURL(url);
				
				}catch(e)
					{
						alert('Error in module_script:'+e.description + ' url:' + url);
					}
					d.innerHTML = '';
				d.innerHTML = module;
				
			}");
			
	}
	function get_user_name($user_id)
	{
		if(isset($user_id))
		{
			$sql ="SELECT username FROM users WHERE user_id = $user_id";
			$r = DB::query($sql);
			$ro = DB::fetch_assoc($r);
			return $ro['username'];
		}else{
			return "unknown";
		}
	}
	
	function style($text)
	{
		return "<style type=\"text/css\">
					$text
				</style>";
	}

	function script($text)
	{
		return "<script type=\"text/javascript\">
					$text
				</script>";
	}
}


?>