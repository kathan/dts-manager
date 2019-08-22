<?php
ini_set('display_errors', 'On');//Debug only
require_once('includes/app.php');
require_once('includes/auth.php');
require_once('includes/dts_table.php');
require_once('includes/Template.php');
require_once('includes/hidden_input.php');
require_once('includes/portal.php');
require_once('includes/calendar.php');
require_once('includes/select_input.php');

class load_table extends dts_table{
	var $load_id;
	var $view_str = 'view';
	var $mod_str='module';
	var $load_str='load';
	var $money_str='money';
	var $cust_str='customer';
	var $problem_str='problem';
	var $rating_str='rating';
	var $order_str='order';
	var $ordered='ordered';
	var $booked='booked';
	var $loaded='loaded';
	var $delivered='delivered';
	var $rate_conf_str = 'rate_conf';
	var $repeat = 'repeat';
	var $warehouse_search_edit ='warehouse_search_edit';
	var $warehouse_search_result = 'warehouse_search_result';
	var $carrier_search_edit ='carrier_search_edit';
	var $carrier_search_result = 'carrier_search_result';
	var $customer_search_edit ='customer_search_edit';
	var $customer_search_result = 'customer_search_result';
	var $row_height='6.5em';
	var $page;
	
	function __construct(){
		parent::__construct('load');
		isset($_REQUEST['page']) ? $this->page = $_REQUEST['page'] : $this->page = 'load';
		
		if(isset($_REQUEST['load_id'])){
			$this->load_id = $_REQUEST['load_id'];
			$this->current_row();
		}
		
		
		$this->hide_delete();
		$this->hide_column('load_id');
		
		
		$i = new hidden_input('page', $this->page);
		$this->add_other_inputs($i);
		$this->add_table_params('page', $this->page);
		
		$col = $this->get_column('rating');

		$col->set_value_list($this->rating_list);//????
		
		$ob = $this->get_column('customer_id');
		$ob->set_parent_label_column('name');
		
		$ob = $this->get_column('order_by');
		$ob->set_parent_label_column('username');
		
		$ob = $this->get_column('carrier_id');
		$ob->set_parent_label_column('name');
		
		$col = $this->get_column('trailer_type');
		$col->set_value_list($this->trailer_type_list);
		
		$col = $this->get_column('class');
		$col->set_value_list($this->load_classes);
		
		$this->tab_menu->add_tab("?page=$this->page&action=$this->search_edit", $this->search);
		$this->tab_menu->add_tab("?page=$this->page", 'Boards');
		$this->tab_menu->add_tab("?page=$this->page&action=$this->all", 'List');
		
	}
	
	function current_row(){
		$sql = "SELECT l.*, (select acct_owner from customer where customer_id = l.customer_id) acct_owner
				FROM `load` l
				WHERE load_id = ?";
		$re = DB::query($sql, [$this->load_id]);
		$r = DB::fetch_assoc($re);
		return $r;
	}
	
	function render(){
		$code = '';
		if(Auth::loggedIn()){
		  
			if(isset($_REQUEST[$this->portal])){
				switch(safe_get($_REQUEST[$this->portal])){
					case $this->load_carrier:
						$code .= $this->get_carriers();
						break;
					case $this->load_warehouse:
						$code .= $this->get_warehouses();
						break;
					case $this->rate_conf_str:
						$code .= $this->get_rate_conf();
						break;
				}			
			}else{
			  
				if(!isset($_REQUEST[SMALL_VIEW])){
					$code .= $this->tab_menu->render();
					$code .= "<div class='tab_sep'></div>";
				}
				
				$code .= "<title>".App::getSiteName()."-Loads</title>";
				$code .= $this->db_script();
				
				$code .= $this->portal_script();
				$code .= $this->module_script();
				$code .= $this->sortable_script();
				$code .= $this->popup_script();
				
				$code .= "<div class='content load_content' id='content'>";
				
				switch(get_action()){//3
					case $this->add_str:
						if($this->add()){
							$this->load_id = $this->last_id;
							$code .= $this->get_load_edit();
						}else{
							$code .= $this->error();
							$code .= $this->feedback;
							$code .= $this->_render_edit();	
						}
						break;
					case $this->search:
						$code .= '<center><h2>Load Search Results</h2>';
						$code .= $this->get_search_results();	
						break;
					case $this->search_edit:
						$code .= '<center><h2>Load Search</h2>';
						$code .= "Use % as a wildcard character";
						$code .= $this->get_search_edit();
						break;
					case $this->warehouse_search_result:
						$code .= $this->get_warehouse_search_result_module();
						$code .= "<input type='button' onclick='window.close();' value='Close'>";
						break;
					case $this->warehouse_search_edit:
						$code .= $this->get_warehouse_search_edit_module();
						$code .= "<input type='button' onclick='window.close();' value='Close'>";
						break;
					case $this->carrier_search_result:
						$code .= $this->get_carrier_search_result_module();
						$code .= "<input type='button' onclick='window.close();' value='Close'>";
						break;
					case $this->carrier_search_edit:
						$code .= $this->get_carrier_search_edit_module();
						$code .= "<input type='button' onclick='window.close();' value='Close'>";

						break;
					case $this->customer_search_result:
						$code .= $this->get_customer_search_result_module();
						$code .= "<input type='button' onclick='window.close();' value='Close'>";
						break;
					case $this->customer_search_edit:
						$code .= $this->get_customer_search_edit_module();
						$code .= "<input type='button' onclick='window.close();' value='Close'>";
						break;
					case $this->view_str:
						switch (safe_get($_REQUEST[$this->mod_str])){
							case $this->load_str:
								$code .= $this->get_load_module();
								break;
							case $this->money_str:
								$code .= $this->get_money_module();
								break;
							case $this->cust_str:
								$code .= $this->get_cust_module();
								break;
							case $this->problem_str:
								$code .= $this->get_problem_module();
								break;
							case $this->rating_str:
								$code .= $this->get_rating_module();
								break;
							case $this->order_str:
								$code .= $this->get_order_module();
								break;
							case $this->rate_conf_str:
								$code .= $this->get_rate_conf();
								break;
							default:
								//This should be the view option
								$code .= $this->gp_script();
								$code .= $this->get_load_edit();
								
								break;
						}
						break;
					case $this->edit_str:
						switch (safe_get($_REQUEST[$this->mod_str])){
							case $this->load_str:
								$code .= $this->get_load_edit_module();
								break;
							case $this->money_str:
								$code .= $this->get_money_edit_module();
								break;
							case $this->cust_str:
								$code .= $this->get_cust_edit_module();
								break;
							case $this->problem_str:
								$code .= $this->get_problem_edit_module();
								break;
							case $this->rating_str:
								$code .= $this->get_rating_edit_module();
								break;
							case $this->order_str:
								$code .= $this->get_order_edit_module();
								break;
							default:
								//This should be the view option
								$code .= $this->gp_script();
								$code .= $this->get_load_edit();
								
								break;
						}
						break;
					case $this->add_str:
						$code .= $this->script("
						function refresh_close(){
							window.opener.get_portal('load', 'load_id=$this->load_id');
							window.close();
						}
						window.onload = refresh_close;");
						$this->add();
						break;
					case $this->repeat:
						$this->repeat_load();
						header("location: ?page=$this->page&action=$this->view_str&load_id=$this->load_id");
						break;
					case $this->new_str:
						$this->create_new();
						if($this->load_id){
							header("location: ?page=$this->page&action=$this->view_str&load_id=$this->load_id");
						}else{
							echo DB::error();
						}
						break;
					case $this->all:
						$code .= '<center><h2>Load List</h2>';
						$code .= $this->get_all_loads();
						break;
					default:
						$code .= $this->load_board();
						break;
				}
				$code .= "</div>";
			}
		}
		return $code;
	}
	
	function repeat_load(){
		
		$old_load_id = $_REQUEST['load_id'];
                $binds = [Auth::getUserId(), $old_load_id];
		$sql = "INSERT INTO `load`(customer_id,trailer_type,pallets,length,size,weight,class,commodity, order_date, order_by)
			SELECT customer_id,trailer_type,pallets,length,size,weight,class,commodity, NOW(), ? FROM `load` WHERE load_id = ?";
		$r = DB::query($sql, $binds);
		if(DB::error()){
			echo DB::error();
			$this->add_error();
			$this->add_error($sql);
		}else{
			$this->load_id = DB::insertid();
		}
		$binds = [$this->load_id, $old_load_id];
		$sql = "INSERT INTO `load_warehouse`(load_id,open_time,close_time,warehouse_id,activity_date,activity_time,type,scheduled_with,creation_date)
			SELECT ?,open_time,close_time,warehouse_id,NOW(),activity_time,type,scheduled_with,NOW() FROM `load_warehouse` WHERE load_id = ?";
		$r = DB::query($sql, $binds);
		if(DB::error()){
			echo DB::error();
			$this->add_error();
			$this->add_error($sql);
		}
	}
	
	function get_all_loads(){
		
		$q = new portal("SELECT	load_id,
								(SELECT name FROM customer c WHERE c.customer_id = l.customer_id) Customer,
								IFNULL(
									(SELECT CONCAT(IF(cancelled,'<span style=\"$this->cancel_style\">',IF(rating='Expedited','<span style=\"$this->expedited_style\">','')),w.state,' ',w.city)
									FROM warehouse w, load_warehouse lw
									WHERE w.warehouse_id = lw.warehouse_id
									AND lw.load_id = l.load_id
									AND lw.type = 'PICK' limit 1)
									, '$this->null_str') origin,
								IFNULL(
									(SELECT CONCAT(IF(cancelled,'<span style=\"$this->cancel_style\">',IF(rating='Expedited','<span style=\"$this->expedited_style\">','')),w.state,' ',w.city)
									FROM warehouse w, load_warehouse lw
									WHERE w.warehouse_id = lw.warehouse_id
									AND lw.load_id = l.load_id
									AND lw.type = 'DEST' limit 1) 
								, '$this->null_str') dest,
								DATE_FORMAT(activity_date, '$this->date_format') activity_date
							FROM `load` l");
	
		$q->set_table('load');
		$q->set_primary_key('load_id');
		$q->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		return $q->render();
	}
	
	function get_search_results(){
		$sql = "SELECT 	l.load_id
						,l.load_type
								,IFNULL(
									(SELECT CONCAT(IF(cancelled,'<span style=\"$this->cancel_style\">',IF(rating='Expedited','<span style=\"$this->expedited_style\">','')),w.state,' ',w.city)
									FROM warehouse w, load_warehouse lw
									WHERE w.warehouse_id = lw.warehouse_id
									AND lw.load_id = l.load_id
									AND lw.type = 'PICK'
									ORDER BY lw.activity_date ASC
									LIMIT 1) 
								, '$this->null_str') origin,
								IFNULL(
									(SELECT CONCAT(IF(cancelled,'<span style=\"$this->cancel_style\">',IF(rating='Expedited','<span style=\"$this->expedited_style\">','')),w.state,' ',w.city)
									FROM warehouse w, load_warehouse lw
									WHERE w.warehouse_id = lw.warehouse_id
									AND lw.load_id = l.load_id
									AND lw.type = 'DEST'
									ORDER BY lw.activity_date DESC
									limit 1) 
								, '$this->null_str') dest
							FROM `load` l ";
		$clause = 'WHERE';
		$where='';
		$binds = [];
		if(isset($_REQUEST['load_id']) && intval($_REQUEST['load_id']) > 0){
                    $binds[] = intval($_REQUEST['load_id']);
                    $where .= " $clause load_id = ?";
                    $clause = 'AND';
		}elseif(isset($_REQUEST['order_number']) && $_REQUEST['order_number'] != ''){
                    $binds[] = $_REQUEST['order_number'];
                    $where .= " $clause load_id in (SELECT load_id FROM load_warehouse WHERE pick_dest_num like ?)";
                    $clause = 'AND';
		}elseif(isset($_REQUEST['bol']) && $_REQUEST['bol'] != ''){
                    $binds[] = $_REQUEST['bol'];
                    $where .= " $clause ltl_number = ?";
                    $clause = 'AND';
		}
		$sql .= $where;
		$re = DB::query($sql, $binds);
		if(DB::error()){
			echo DB::error()."<br>";
			echo $sql;
		}
		
		$t = new Template();
		$t->error_reporting = E_ALL & ~E_NOTICE;
		$t->assign('loads', DB::to_array($re));
		return $t->fetch(App::getTempDir().'load_search_result.tpl');
	}
	
	function get_search_edit(){
		$t = new Template();
		$t->error_reporting = E_ALL & ~E_NOTICE;
		
		return $t->fetch(App::getTempDir().'load_search_form.tpl');
	}
	
	function get_carriers(){
		$c ='';
		
		$sql = "SELECT	c.carrier_id, phys_address,
                            CONCAT('<a href=\"#\" onclick=\"javascript:popUp(\'?page=load_carrier&action=$this->edit_str&load_id=$this->load_id&carrier_id=',c.carrier_id,'&".SMALL_VIEW."\', \'load_carrier_".$this->load_id."_',c.carrier_id,'\', 960, 350)\">',name,'</a>') name,
                            CONCAT('<center><input type=\"button\" value=\"Rate Conf\" onclick=\"javascript:open_rate_conf(\'?page=$this->page&portal=$this->rate_conf_str&load_id=$this->load_id&carrier_id=',c.carrier_id,'&".SMALL_VIEW."\', \'load_carrier_".$this->load_id."_',c.carrier_id,'\')\">') rate_conf_button ";

		
		if(Auth::loggedInAs('admin')){
			$sql .= " ,CONCAT(	'<a href=\"#\" onclick=\"delete_carrier(',
							c.carrier_id,
							')\">$this->delete_icon</a>') `delete`";
		}
		$sql .= ",lc.carrier_id, CONCAT(main_phone_number, '<br>', fax) phone_fax,  (SELECT username FROM `users` u WHERE u.user_id =lc.booked_with) booked_with, lc.notes
					FROM `load` l, carrier c, load_carrier lc
					WHERE l.load_id = ?
					AND lc.load_id = l.load_id
					AND c.carrier_id = lc.carrier_id";
		$re = DB::query($sql, [$this->load_id]);
		$r = DB::fetch_assoc($re);
		
		$c .= "<table style='width:100%;border:1px solid black;' class='content'><tr>\n";
		$c .= "<th>Carrier Name</td>\n";
		$c .= "<th>Address</td>\n";
		$c .= "<th>Phone<br>Fax</td>\n";
		$c .= "<th>Booked With</td>\n";
		$c .= "<th>Rate Confirmation</td>\n";
		if(Auth::loggedInAs('admin')){
			$c .= "<th>Delete</td>\n";
		}
		$c .= "</tr><tr>";
		$c .= "<td class='faux_edit'>$r[name]</td>\n";
		$c .= "<td class='faux_edit'>$r[phys_address]</td>\n";
		$c .= "<td class='faux_edit'>$r[phone_fax]</td>\n";
		$c .= "<td class='faux_edit'>$r[booked_with]</td>\n";
		$c .= "<td class=''>$r[rate_conf_button]</td>\n";
		if(Auth::loggedInAs('admin')){
			$c .= "<td class='border'>$r[delete]</td>\n";
		}
		$c .= "</tr></table>\n";
		
		return $c;
	}
	
	function fetch_warehouses(){
		$sql = "SELECT	*
			, IFNULL(DATE_FORMAT(close_time, '$this->time_format'), CASE DAYNAME(activity_date)
				WHEN 'Sunday' THEN DATE_FORMAT(sun_close_time, '$this->time_format')
				WHEN 'Monday' THEN DATE_FORMAT(mon_close_time, '$this->time_format')
				WHEN 'Tuesday' THEN DATE_FORMAT(tues_close_time, '$this->time_format')
				WHEN 'Wednesday' THEN DATE_FORMAT(wed_close_time, '$this->time_format')
				WHEN 'Thursday' THEN DATE_FORMAT(thurs_close_time, '$this->time_format')
				WHEN 'Friday' THEN DATE_FORMAT(fri_close_time, '$this->time_format')
				WHEN 'Saturday' THEN DATE_FORMAT(sat_close_time, '$this->time_format')
				ELSE 'N/A'
			END) close_time
			,IFNULL(DATE_FORMAT(open_time, '$this->time_format'), CASE DAYNAME(activity_date)
				WHEN 'Sunday' THEN DATE_FORMAT(sun_open_time, '$this->time_format')
				WHEN 'Monday' THEN DATE_FORMAT(mon_open_time, '$this->time_format')
				WHEN 'Tuesday' THEN DATE_FORMAT(tues_open_time, '$this->time_format')
				WHEN 'Wednesday' THEN DATE_FORMAT(wed_open_time, '$this->time_format')
				WHEN 'Thursday' THEN DATE_FORMAT(thurs_open_time, '$this->time_format')
				WHEN 'Friday' THEN DATE_FORMAT(fri_open_time, '$this->time_format')
				WHEN 'Saturday' THEN DATE_FORMAT(sat_open_time, '$this->time_format')
				ELSE 'N/A'
			END) open_time
			
			,DAYNAME(activity_date) day_name
			,DATE_FORMAT(activity_date, '$this->date_format') activity_date
			FROM load_warehouse lw, warehouse w
			WHERE lw.warehouse_id = w.warehouse_id
			AND load_id = ?
			ORDER BY type desc, lw.creation_date asc";
		$r = DB::query($sql, [$this->load_id]);
		return $r;
	}
	
	function get_warehouses(){
		$t = new Template();
		$t->error_reporting = E_ALL & ~E_NOTICE;
		$t->assign('lw', DB::to_array($this->fetch_warehouses()));
		$t->assign('times', $this->times);
		$t->assign('admin', Auth::loggedInAs('admin'));
		return $t->fetch(App::getTempDir().'load_warehouse_mod.tpl');
	}
	
	function get_time_drop_down(){
		$c = "CONCAT('<select  id=\"action=update&table=load_warehouse&warehouse_id=',w.warehouse_id,'&load_id=', load_id, '&close_time=\" name=\"close_time\" value=\"',TIME_FORMAT(close_time, '%l:%i %p'),'\"  onchange=\"db_save(this);column_updated(this);update_warehouse_portal();\"></select>') close_time,";
	}
	
	function get_money_edit_module_new(){
		$r = $this->current_row();
		print_r($r);
	}
	
	function get_money_edit_module(){
		//width 1064
		//height =240
		$r = $this->current_row();
		
		$c ='';
		$c .= "<table width='100%' border=0><tr><td width='50%'>";
		$c .= "<table width='100%' border=0><tr>
				<th colspan=4>Customer</th></tr>
				<tr>
				<th colspan=1></th>
				<th colspan=1>Amount</th>
				<th colspan=1>Rate</th>
				<th colspan=1>Extended</th>";
		$c .= '</tr><tr>';
		
		$c .= '<td>Line Haul:</td>';
		$c .= '<td>'.$this->fetch_edit('cust_line_haul_amount', $r['cust_line_haul_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('cust_line_haul', $r['cust_line_haul']).'</td>';
		$c .= '<td class="right faux_edit" id="cust_line_haul_extended"></td>';
		$c .= '</tr><tr>';
		
		$c .= '<td>Detention:</td>';
		$c .= '<td>'.$this->fetch_edit('cust_detention_amount', $r['cust_detention_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('cust_detention', $r['cust_detention']).'</td>';
		$c .= '<td class="right faux_edit" id="cust_detention_extended"></td>';
		$c .= '</tr><tr>';
		
		$c .= '<td>TONU</td>';
		$c .= '<td>'.$this->fetch_edit('cust_tonu_amount', $r['cust_tonu_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('cust_tonu', $r['cust_tonu']).'</td>';
		$c .= '<td class="right faux_edit" id="cust_tonu_extended"></td>';
		$c .= '</tr><tr>';
		
		$c .= '<td>Unload/Load</td>';
		$c .= '<td>'.$this->fetch_edit('cust_unload_load_amount', $r['cust_unload_load_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('cust_unload_load', $r['cust_unload_load']).'</td>';
		$c .= '<td class="right faux_edit" id="cust_unload_load_extended"></td>';
		$c .= '</tr><tr>';
		
		$c .= '<td>Fuel</td>';
		$c .= '<td>'.$this->fetch_edit('cust_fuel_amount', $r['cust_fuel_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('cust_fuel', $r['cust_fuel']).'</td>';
		$c .= '<td class="right faux_edit" id="cust_fuel_extended"></td>';
		$c .= '</tr><tr>';
		
		$c .= '<td>Other</td>';
		$c .= '<td>'.$this->fetch_edit('cust_other_amount', $r['cust_other_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('cust_other', $r['cust_other']).'</td>';
		$c .= '<td class="right faux_edit" id="cust_other_extended"></td>';
		$c .= "</tr></table>";
		$c .= "</td><td>";
		$c .= "<table width='100%' border=0><tr>
				<th colspan=4>Carrier</th></tr>
				<tr>
				<th colspan=1></th>
				<th colspan=1>Amount</th>
				<th colspan=1>Rate</th>
				<th colspan=1>Extended</th>";
		$c .= '</tr><tr>';
		
		$c .= '<td>Line Haul:</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_line_haul_amount', $r['carrier_line_haul_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_line_haul', $r['carrier_line_haul']).'</td>';
		$c .= '<td class="right faux_edit" id="carrier_line_haul_extended"></td>';
		
		$c .= '</tr><tr>';
		
		$c .= '<td>Detention:</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_detention_amount', $r['carrier_detention_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_detention', $r['carrier_detention']).'</td>';
		$c .= '<td class="right faux_edit" id="carrier_detention_extended"></td>';
		$c .= '</tr><tr>';
		
		$c .= '<td>TONU</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_tonu_amount', $r['carrier_tonu_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_tonu', $r['carrier_tonu']).'</td>';
		$c .= '<td class="right faux_edit" id="carrier_tonu_extended"></td>';
		$c .= '</tr><tr>';
		
		$c .= '<td>Unload/Load</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_unload_load_amount', $r['carrier_unload_load_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_unload_load', $r['carrier_unload_load']).'</td>';
		$c .= '<td class="right faux_edit" id="carrier_unload_load_extended"></td>';
		$c .= '</tr><tr>';
		
		$c .= '<td>Fuel</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_fuel_amount', $r['carrier_fuel_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_fuel', $r['carrier_fuel']).'</td>';
		$c .= '<td class="right faux_edit" id="carrier_fuel_extended"></td>';
		$c .= '</tr><tr>';
		
		$c .= '<td>Other</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_other_amount', $r['carrier_other_amount']).'</td>';
		$c .= '<td>'.$this->fetch_edit('carrier_other', $r['carrier_other']).'</td>';
		$c .= '<td class="right faux_edit" id="carrier_other_extended"></td>';
		
		$c .= "</tr></table>";
		$c .= "</tr></table>";	
		$c .= "<input type='button' onclick='refresh_close()' value='Close'>";
		$c .= $this->script("
				function column_updated(o){
					if(o.name == 'cust_line_haul_amount' || o.name == 'cust_line_haul'){
						update_ext('cust_line_haul_extended', 'cust_line_haul_amount', 'cust_line_haul');
					}else if(o.name == 'carrier_line_haul_amount' || o.name == 'carrier_line_haul'){
						update_ext('carrier_line_haul_extended', 'carrier_line_haul_amount', 'carrier_line_haul');
					}else if(o.name == 'cust_detention_amount' || o.name == 'cust_detention'){
						update_ext('cust_detention_extended', 'cust_detention_amount', 'cust_detention');
					}else if(o.name == 'carrier_detention_amount' || o.name == 'carrier_detention'){
						update_ext('carrier_detention_extended', 'carrier_detention_amount', 'carrier_detention');
					}else if(o.name == 'cust_tonu_amount' || o.name == 'cust_tonu'){
						update_ext('cust_tonu_extended', 'cust_tonu_amount', 'cust_tonu');
					}else if(o.name == 'carrier_tonu_amount' || o.name == 'carrier_tonu'){
						update_ext('carrier_tonu_extended', 'carrier_tonu_amount', 'carrier_tonu');
					}else if(o.name == 'cust_unload_load_amount' || o.name == 'cust_unload_load'){
						update_ext('cust_unload_load_extended', 'cust_unload_load_amount', 'cust_unload_load');
					}else if(o.name == 'carrier_unload_load_amount' || o.name == 'carrier_unload_load'){
						update_ext('carrier_unload_load_extended', 'carrier_unload_load_amount', 'carrier_unload_load');
					}
					else if(o.name == 'cust_fuel_amount' || o.name == 'cust_fuel'){
						update_ext('cust_fuel_extended', 'cust_fuel_amount', 'cust_fuel');
					}else if(o.name == 'carrier_fuel_amount' || o.name == 'carrier_fuel'){
						update_ext('carrier_fuel_extended', 'carrier_fuel_amount', 'carrier_fuel');
					}else if(o.name == 'cust_other_amount' || o.name == 'cust_other'){
						update_ext('cust_other_extended', 'cust_other_amount', 'cust_other');
					}else if(o.name == 'carrier_other_amount' || o.name == 'carrier_other'){
						update_ext('carrier_other_extended', 'carrier_other_amount', 'carrier_other');
					}
				}
				
				function update_ext(ext_name, amt_name, rate_name){
					var ext = document.getElementById(ext_name);
					var rate = document.getElementsByName(rate_name)[0];
					var amount = document.getElementsByName(amt_name)[0];
					ext.innerHTML = '$' + amount.value * rate.value;
					
				}
				function refresh_close(){
					window.opener.update_money_module();
					window.close();
				}
				update_ext('cust_line_haul_extended', 'cust_line_haul_amount', 'cust_line_haul');
				update_ext('carrier_line_haul_extended', 'carrier_line_haul_amount', 'carrier_line_haul');
				update_ext('cust_detention_extended', 'cust_detention_amount', 'cust_detention');
				update_ext('carrier_detention_extended', 'carrier_detention_amount', 'carrier_detention');
				update_ext('cust_tonu_extended', 'cust_tonu_amount', 'cust_tonu');
				update_ext('carrier_tonu_extended', 'carrier_tonu_amount', 'carrier_tonu');
				update_ext('cust_unload_load_extended', 'cust_unload_load_amount', 'cust_unload_load');
				update_ext('carrier_unload_load_extended', 'carrier_unload_load_amount', 'carrier_unload_load');
				update_ext('cust_fuel_extended', 'cust_fuel_amount', 'cust_fuel');
				update_ext('carrier_fuel_extended', 'carrier_fuel_amount', 'carrier_fuel');
				update_ext('cust_other_extended', 'cust_other_amount', 'cust_other');
				update_ext('carrier_other_extended', 'carrier_other_amount', 'carrier_other');
				");
		return $c;
	}
	
	function get_problem_edit_module(){
		$r = $this->current_row();
		$c ='';
		$c .= "<table width='100%' border=0>";
		$c .= '<tr><td>Problem</td><td>'.$this->fetch_edit('problem', $r['problem']).'</td><tr>';
		$c .= '<tr><td>Solution</td><td>'.$this->fetch_edit('solution', $r['solution']).'</td></tr>';
		$c .= "</table>";
		$c .= "<form>
					<input type='button' onclick='refresh_close()' value='Close'>
					</form>";
		$c .= $this->script("
				function refresh_close(){
					db_save(document.getElementsByName('problem')[0]);
					window.opener.update_problem_module();
					window.close();
				}");
		
		return $c;
	}
	
	function get_warehouse_search_result_module(){
		
		$c ='';
		$c .= $this->get_warehouse_search_edit_module();
		$c .= "Warehouse Search Results<br>";
		$c .= "Select Type: ".$this->get_warehouse_type_select();
		$c .= $this->script("
					
					function add_load_warehouse(warehouse_id){
						if(window.opener){
							var type = document.getElementById('warehouse_type');
							var obj=new Object();
							obj.id = 'table=load_warehouse&creation_date=NOW()&scheduled_with=".Auth::getUserId()."&type='+type.value+'&warehouse_id='+warehouse_id+'&load_id=';
							obj.value = $this->load_id;
							db_save(obj);
							refresh_close();
						}else{
							alert('You already closed the parent window.');
						}
					}
					function refresh_close(){
						window.opener.update_warehouse_portal();
					}");
		$sql = "SELECT CONCAT('D',warehouse_id) warehouse_id, name, address, city, state, CONCAT('<input type=\"button\" value=\"Add\" onclick=\"add_load_warehouse(',warehouse_id,')\">') `add` FROM warehouse ";
		$clause = 'WHERE';
		$where='';
		
		if(isset($_REQUEST['warehouse_id']) && intval(trim($_REQUEST['warehouse_id'], 'd D')) > 0){
			$where .= " $clause warehouse_id = ".intval(trim($_REQUEST['warehouse_id'], 'd D'));
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != ''){
				$where .= " $clause name like '$_REQUEST[name]'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['address']) && $_REQUEST['address'] !=''){
				$where .= " $clause address like '$_REQUEST[address]'";
			}
			
			if(isset($_REQUEST['city']) && $_REQUEST['city'] !=''){
				$where .= " $clause city like '$_REQUEST[city]'";
			}
			
			if(isset($_REQUEST['state']) && $_REQUEST['state'] !=''){
				$where .= " $clause state like '$_REQUEST[state]'";
			}
		}
		$sql .= $where;
		$p = new portal($sql);
		$c .= $p->render();
		
		return $c;
	}
	
	function get_warehouse_search_edit_module(){
		
		$wt = new table('warehouse');
		
		$si = new submit_input($this->warehouse_search_result, 'action');
		$wt->set_submit_input($si);
		
		
		$hi1 = new hidden_input(SMALL_VIEW);
		$wt->add_input($hi1);
		
		$hi2 = new hidden_input('page', $this->page);
		$wt->add_input($hi2);
		
		$hi3 = new hidden_input('load_id', $this->load_id);
		$wt->add_input($hi3);
		
		$f = $wt->get_form();
		$f->set_get();
	
		$wt->omit_all_columns();
		
		$wt->insert_column('warehouse_id');
		$col = $wt->get_column('warehouse_id');
		$o = $col->get_edit_html();
		$o->set_size('5');
		echo $test2->size;
		
		$wt->insert_column('name');
		$wt->insert_column('city');
		$wt->insert_column('state');
		$c = "Search Warehouses";// for $cust[name]";
		$c .= $wt->_render_edit();
		return $c;
	}
	
	//===== Carrier Search functions =====
	function get_carrier_search_result_module(){
		
		$c ='';
		$c .= $this->get_carrier_search_edit_module();
		$c .= "Carrier Search Results<br>";
		
		$c .= $this->script("
					function check_carrier(insurance_expires){
						
						ins_exp_str = insurance_expires.split('-');
						ins_exp = new Date();
						ins_exp.setYear(ins_exp_str[0]);
						ins_exp.setMonth(ins_exp_str[1]);
						ins_exp.setDate(ins_exp_str[2]);
						today = new Date();
						if(ins_exp > today){
							return true;
						}else
						{
							return false;
						}
					}
					function add_load_carrier(carrier_id, insurance_expires){
						if(check_carrier(insurance_expires)){
							var param_str = 'table=load_carrier&carrier_id='+carrier_id+'&load_id=';
							var obj=new Object();
							obj.id  = param_str;
							obj.value = $this->load_id;
							db_save(obj);
							refresh_close();
						}else{
							alert(\"Carrier's insurance expired on \"+insurance_expires+\".\")
						}
					}
					function refresh_close(){
						window.opener.update_carrier_portal();
					}");
		$sql = "SELECT	CONCAT('S', carrier_id) carrier_id,
						name,
						phys_city,
						phys_state,";
		$sql .= "		IF(insurance_expires > NOW(), IF(!do_not_load, CONCAT('<input type=\"button\" value=\"Add\" onclick=\"add_load_carrier(',carrier_id,',\'',insurance_expires,'\')\">'), null), 'Insurance Expired') `add` ";
		$sql .= "FROM carrier ";
		$clause = 'WHERE';
		$where='';
		if(isset($_REQUEST['carrier_id']) && intval(trim($_REQUEST['carrier_id'], 's S')) > 0){
			$where .= " $clause carrier_id = ".intval(trim($_REQUEST['carrier_id'], 's S'));
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != ''){
				$where .= " $clause name like '$_REQUEST[name]'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['phys_address']) && $_REQUEST['phys_address'] !=''){
				$where .= " $clause phys_address like '$_REQUEST[phys_address]'";
			}
			if(isset($_REQUEST['phys_city']) && $_REQUEST['phys_city'] !=''){
				$where .= " $clause phys_city like '$_REQUEST[phys_city]'";
			}
			if(isset($_REQUEST['phys_state']) && $_REQUEST['phys_state'] !=''){
				$where .= " $clause phys_state like '$_REQUEST[phys_state]'";
			}
			if(isset($_REQUEST['mc_number']) && $_REQUEST['mc_number'] !=''){
				$where .= " $clause mc_number like '$_REQUEST[mc_number]'";
			}
		}
		$sql .= $where;
		$p = new portal($sql);
		$c .= $p->render();
		
		return $c;
	}
	
	function get_carrier_search_edit_module(){
		
		$wt = new table('carrier');
		
		$si = new submit_input($this->carrier_search_result, 'action');
		$wt->set_submit_input($si);
		
		$hi1 = new hidden_input(SMALL_VIEW);
		$wt->add_input($hi1);
		
		$hi2 = new hidden_input('page', $this->page);
		$wt->add_input($hi2);
		
		$hi3 = new hidden_input('load_id', $this->load_id);
		$wt->add_input($hi3);
		
		$f = $wt->get_form();
		$f->set_get();
	
		$wt->omit_all_columns();
		$wt->unhide_column('carrier_id');
		$wt->insert_column('carrier_id');
		$wt->insert_column('name');
		$wt->insert_column('phys_city');
		$wt->insert_column('phys_state');
		$wt->insert_column('mc_number');
		return $wt->_render_edit();
	}
	
	//===== Customer Search functions =====
	function get_customer_search_result_module(){
		$sql = "SELECT	CONCAT('T', customer_id) p_customer_id,
						customer_id,
						name,
						address,
						city,
						state,
						account_status,
						CONCAT('<input type=\"button\" value=\"Add\" onclick=\"set_customer(',customer_id,')\">') `add` FROM customer ";
		$clause = 'WHERE';
		$where='';
		if(isset($_REQUEST['customer_id']) && intval(trim($_REQUEST['customer_id'], 't T')) > 0){
			$where .= " $clause customer_id = ".intval(trim($_REQUEST['customer_id'], 't T'));
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != ''){
				$where .= " $clause name like '$_REQUEST[name]'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['address']) && $_REQUEST['address'] !=''){
				$where .= " $clause address like '$_REQUEST[address]'";
			}
			if(isset($_REQUEST['city']) && $_REQUEST['city'] !=''){
				$where .= " $clause city like '$_REQUEST[city]'";
			}
			if(isset($_REQUEST['state']) && $_REQUEST['state'] !=''){
				$where .= " $clause state like '$_REQUEST[state]'";
			}
			
		}
		if(!Auth::loggedInAs('admin') && !Auth::loggedInAs('super admin')){
			$where .= " $clause acct_owner = ".Auth::getUserId();
			$clause = 'AND';
		}
		$sql .= $where;
		$re = DB::query($sql);
		$t = new Template();
		$t->error_reporting = E_ALL & ~E_NOTICE;
		$t->assign('cust', DB::to_array($re));
		return $t->fetch(App::getTempDir().'/cust_search_result.tpl');
	}
	
	function get_customer_search_edit_module(){
		$c = '<center><h2>Customer Search</h2>';
		$c .= "Use % as a wildcard character";
		
		$wt = new table('customer');
		
		$si = new submit_input($this->search);
		$wt->set_submit_input($si);
		
		$hi1 = new hidden_input(SMALL_VIEW);
		$wt->add_input($hi1);
		
		$hi2 = new hidden_input('page', $this->page);//test
		$wt->add_input($hi2);
		
		$hi3 = new hidden_input('action', $this->customer_search_result);
		$wt->add_input($hi3);
		
		$hi4 = new hidden_input('load_id', $this->load_id);
		$wt->add_input($hi4);
		
		$f = $wt->get_form();
		$f->set_get();
	
		$wt->omit_all_columns();
		$wt->insert_column('customer_id');
		$wt->insert_column('name');
		$wt->insert_column('city');
		$wt->insert_column('state');
		
		$c .= $wt->_render_edit();
		
		return $c;
	}

	function get_problem_module(){
		$r = $this->current_row();
		$c = "<table width='100%' border=0 class='content'>";
		$c .= "<tr><td>Problem:</td><td class='faux_edit'>$r[problem]</td><tr>";
		$c .= "<tr><td>Solution:</td><td class='faux_edit'>$r[solution]</td></tr>";
		$c .= "</table>";
	
		return $c;
	}
	
	function get_money_module(){
		
		$r = $this->current_row();
		$cust_line_haul_total = $r['cust_line_haul'] * $r['cust_line_haul_amount'];
		$cust_detention_total = $r['cust_detention'] * $r['cust_detention_amount'];
		$cust_tonu_total = $r['cust_tonu'] * $r['cust_tonu_amount'];
		$cust_unload_load_total = $r['cust_unload_load'] * $r['cust_unload_load_amount'];
		$cust_fuel_total = $r['cust_fuel'] * $r['cust_fuel_amount'];
		$cust_other_total = $r['cust_other'] * $r['cust_other_amount'];
		
		$customer_total = $cust_line_haul_total + $cust_detention_total + $cust_tonu_total + $cust_unload_load_total + $cust_fuel_total + $cust_other_total;
		$carrier_line_haul_total = $r['carrier_line_haul'] * $r['carrier_line_haul_amount'];
		$carrier_detention_total = $r['carrier_detention'] * $r['carrier_detention_amount'];
		$carrier_tonu_total = $r['carrier_tonu'] * $r['carrier_tonu_amount'];
		$carrier_unload_load_total = $r['carrier_unload_load'] * $r['carrier_unload_load_amount'];
		$carrier_fuel_total = $r['carrier_fuel'] * $r['carrier_fuel_amount'];
		$carrier_other_total = $r['carrier_other'] * $r['carrier_other_amount'];
		
		$carrier_total = $carrier_line_haul_total + $carrier_detention_total + $carrier_tonu_total + $carrier_unload_load_total + $carrier_fuel_total + $carrier_other_total;
		$gp = ($customer_total - $carrier_total);
		
		$gp > 0 ? $gpp = (($gp * 100) / ($customer_total)) : $gpp = 0;
		
		$t = new Template();
		$t->error_reporting = E_ALL & ~E_NOTICE;
		$t->assign('load', $r);
		$t->assign('cust_line_haul_total', $cust_line_haul_total);
		$t->assign('cust_detention_total', $cust_detention_total);
		$t->assign('cust_tonu_total', $cust_tonu_total);
		$t->assign('cust_unload_load_total', $cust_unload_load_total);
		$t->assign('cust_fuel_total', $cust_fuel_total);
		$t->assign('cust_other_total', $cust_other_total);
		
		$t->assign('customer_total', $customer_total);
		
		$t->assign('carrier_line_haul_total', $carrier_line_haul_total);
		$t->assign('carrier_detention_total', $carrier_detention_total);
		$t->assign('carrier_tonu_total', $carrier_tonu_total);
		$t->assign('carrier_unload_load_total', $carrier_unload_load_total);
		$t->assign('carrier_fuel_total', $carrier_fuel_total);
		$t->assign('carrier_other_total', $carrier_other_total);
		
		$t->assign('carrier_total', $carrier_total);
		$wcp = round($gp * ($r['wc_percent'] * .01), 2);
		$dlsp = round($gp * ($r['dls_percent'] * .01), 2);
		
		$dtsp = round($gp - $wcp, 2);
		$t->assign('gp', $gp);
		$t->assign('wcp', $wcp);
		$t->assign('dtsp', $dtsp);
		return $t->fetch(App::getTempDir().'money_load.tpl');
	}
		
	function get_cust_module(){
		$r = $this->current_row();
		$c = "<table style='width:100%;border:1px solid black;' class='content'><tr>";
		if(isset($r['customer_id'])){
			$cust = $this->get_customer($r['customer_id']);
			
			$c .= "<th>ID</td>";
			$c .= "<th>Name</td>";
			$c .= "<th>City</td>";
			$c .= "<th>State</td>";
			$c .= "<th>Customer Rep</td>";
			$c .= "</tr><tr>";
			$c .= "<td class='bold faux_edit'>T$cust[customer_id]</td>";
			$c .= "<td class='bold faux_edit'>$cust[name]</td>";
			$c .= "<td class='bold faux_edit'>$cust[city]</td>";
			$c .= "<td class='bold faux_edit'>$cust[state]</td>";
			$c .= "<td class='bold faux_edit'>".$this->get_acct_owner_name()."</td>";
			
		}else{
			$c .= "<td>$this->null_str</td>";
		}
		
		$c .= "</tr></table>";
	
		return $c;
	}
	
	function get_rating_edit_module(){
		$r = $this->current_row();
		$c = "<table width='100%' border=0>";
		$col = $this->get_column('rating');
		$rating_list = Array('Standard' => 'Standard', 'Expedited' => 'Expedited');
		$col->set_value_list($rating_list);
		$c .= '<tr><td>Rating Code</td><td>'.$this->fetch_edit('rating', $r['rating']).'</td><tr>';
		$c .= '<tr><td>'.$this->fetch_edit('cancelled', $r['cancelled']).'</td><td>Cancel</td></tr>';
		
		$c .= "</table>";
		$c .= "<form>
					<input type='button' onclick='refresh_close()' value='Close'>
					</form>";
		$c .= $this->script("
				function refresh_close(){
					window.opener.update_rating_module();
					window.close();
				}");
		
		return $c;
	}
	
	function get_rating_module(){
		$r = $this->current_row();
		if($r['cancelled']){
			$cancelled = 'Yes';
		}else{
			$cancelled = 'No';
		}
		$c = "<table><tr><td>";
		$c .= "<tr><td>Rating Code</td><td class='faux_edit'>$r[rating]</td><tr>";
		$c .= "<tr><td>Cancel</td><td class='faux_edit'>$cancelled</td></tr>";
		
		$c .= "</td></tr></table>";
	
		return $c;
	}
	
	function get_cust_edit_module(){
		$r = $this->current_row();
		$c = "<table><tr>";
		$c .= "<td>".$this->fetch_edit('customer_id', $r['customer_id'])."</td>";
		$c .= "</tr></table>";
		$c .= "<form>
					<input type='button' onclick='refresh_close()' value='Close'>
					</form>";
		$c .= $this->script("
				function refresh_close(){
					window.opener.update_cust_module();
					window.close();
				}");
		return $c;
	}
	
	function get_load_edit_module(){
		$r = $this->current_row();
		$c = "<table width='100%' border=1><tr>";
		$c .= "<td class='label_class'>Trailer Type</td><td>".$this->fetch_edit('trailer_type', $r['trailer_type'])."</td>";
		$c .= "<td class='label_class'>Length (inches)</td><td>".$this->fetch_edit('length', $r['length'])."</td>";
		$c .= "<td class='label_class'>Size</td><td>".$this->fetch_edit('size', $r['size'])."</td>";
		$c .= "</tr><tr>";
		$c .= "<td class='label_class'>Pallets</td><td>".$this->fetch_edit('pallets', $r['pallets'])."</td>";
		$c .= "<td class='label_class'>Weight (lbs.)</td><td>".$this->fetch_edit('weight', $r['weight'])."</td>";
		$c .= "<td class='label_class'>Class</td><td>".$this->fetch_edit('class', $r['class'])."</td>";
		$c .= "</tr></table>";
		$c .= "<form>
					<input type='button' onclick='refresh_close()' value='Close'>
					</form>";
		$c .= $this->script("
				function refresh_close(){
					window.opener.update_load_module();
					window.close();
				}");
		return $c;
	}
	
	function get_order_edit_module(){
		$r = $this->current_row();
		$c = "<table width='100%' border=0>";
		$c .= '<tr><td>Order By</td><td>'.$this->fetch_edit('order_by', $r['order_by']).'</td></tr>';
		$c .= "</table>";
		$c .= "<form>
					<input type='button' onclick='refresh_close()' value='Close'>
					</form>";
		$c .= $this->script("
				function refresh_close(){
					window.opener.update_order_module();
					window.close();
				}");
		return $c;
	}
	
	function get_order_module(){
		$r = $this->current_row();
		$user = $this->get_user($r['order_by']);
		$c = "<table width='100%' border=0>";
		$c .= "<tr><td>Order By</td><td class='faux_edit'>$user[username]</td></tr>";
		$c .= "</table>";
	
		return $c;
	}
	
	function get_load_edit(){
		
		$r = $this->current_row();
		if($r['cancelled']){
			$c = $this->style("
				.load_content{background-color:$this->cancel_color}");
		}else{
			$c = $this->style("
				.load_content, .content{background-color:#EEEEEE}");
		}
		$auth_edit=false;
		if(!$this->been_delivered() && (Auth::loggedInAs('admin') || Auth::getUserId() === $r['order_by'] || Auth::getUserId() === $r['acct_owner'])){
			$auth_edit=true;
		}
		$c .= $this->popup_script();
		$c .= "<center><h2>#$this->load_id</h2>";
		
		//==== Repeat Load Button ====
		$c .= "<form method='post' action='?page=$this->page&action=$this->repeat&load_id=$this->load_id'><input type='submit' value='Repeat'></form>";
		
						
		$c .= "<table width='100%'><tr>";
		$c .= "<td valign='top' width='33%' class='bottom_pad'>";
		
		//==== Order ====
		$c .= "
		<fieldset style='height:$this->row_height'>
			<legend>Order</legend>
			<div id='order_module'>
				<table width='100%' border=0>
					<tr>
						<td>Order By:</td>";
		if(Auth::loggedInAs('admin')){
			$col = $this->get_column('order_by');
			$col->set_value_list($this->get_users());
			$c .= "				<td>".$this->fetch_edit('order_by', $r['order_by'])."</td>";
		}else{
			$c .= "				<td>". $this->get_order_by_name()."</td>";
		}
		$c .= "			</tr>
					<tr>
						<td>Ordered Date:</td>
						<td>".MySQL_Date_To_format($r['order_date'], $this->php_date_format)."</td>
					</tr>
					<tr>
						<td>Activity Date</td>
						<td>".$this->fetch_edit('activity_date', $r['activity_date'])."</td>
					</tr>
				</table>
			</div>
		</fieldset>";
		//==================
		
		$c .= "</td>";
		$c .= "<td valign='top' width='33%' class='bottom_pad'>";
		
		//====Zone====
		$col = $this->get_column('zone');
		$zone_list = $this->get_zones();
		
		if(isset($col)){
			$col->set_value_list($zone_list);
		}
		$c .= "
		<fieldset style='height:$this->row_height'>
			<legend>Zone</legend>";
		$c .= "
			<div id='zone_module'>";
		$c .= "
				<table width='100%' border=0>
					<tr>
						<td class='right'>Zone:</td>
						<td class='left'>".$this->fetch_edit('zone', $r['zone'])."</td>
					<tr>
					<tr>
						<td class='right'>".$this->fetch_edit('cancelled', $r['cancelled'])."</td>
						<td class='left'>Cancel</td>
					</tr>
				</table>
			</div>
		</fieldset>";
		
		$c .= $this->script("
				set_content_color();
				var cont;
				function set_content_color(){
					if(!cont){
						cont = document.getElementById('content');
					}
					var cancelled = document.getElementsByName('cancelled');
					cancelled = cancelled[0];
					
					if(cancelled.checked){
						cont.style.backgroundColor = '$this->cancel_color';
					}else{
						cont.style.backgroundColor = '$this->content_color';
					}
					return true;
				}");
		
		//==================
			$c .= "</td>
					<td valign='top' width='33%' class='bottom_pad'>";
		//====Problem====
		$c .= "
		<fieldset style='height:$this->row_height'>
				<legend>";
		if($auth_edit){
			$c .= "<a href='#' onclick=\"javascript:popUp('?page=$this->page&action=$this->edit_str&module=$this->problem_str&load_id=$this->load_id&".SMALL_VIEW."');return false;\">Problem</a>";
		}else{
			$c .= 'Problem';
		}
		$c .= "</legend>
				<div id='problem_module'></div>
		</fieldset>";
		$c .= $this->script("
					function update_problem_module(){
						get_module('problem', 'action=view&load_id=$this->load_id');
					}
					jQuery().ready(function(){
						update_problem_module();
					});
					");
		
		//==================
		$c .= "	</td>
				<td valign='top'>";
		$c .= "	</td>
				</tr>
				<tr>
				<td colspan=3 class='bottom_pad'>";
		//==== Customer ====
		if(Auth::loggedInAs('admin') || Auth::getUserId() == $r['order_by'] || Auth::getUserId() == $r['acct_owner']){
		$c .= "
		<fieldset>
				<legend>";
		$c .="</legend>
			<div id='customer_module'></div>
		</fieldset>";
		$c .= $this->script("
					function update_cust_module(){
						get_module('customer', 'action=view&load_id=$this->load_id');
					}
					jQuery().ready(function(){
						update_cust_module();
					});
					");
		
		//============
		$c .= "</td></tr><tr><td colspan=3 class='bottom_pad'>";
		}
		//====Load====
		$lt = $this->get_column('load_type');
		$lt->set_value_list(dts_table::$load_type);
		$c .= "
		<fieldset>
				<legend>Load</legend>
					<div id='load_module'>";
		$c .= "<table width='100%'><tr>";
		$c .= "<td class='label_class'>Trailer Type:</td><td>".$this->fetch_edit('trailer_type', $r['trailer_type'])."</td>";
		$c .= "<td class='label_class'>Pallets:</td><td>".$this->fetch_edit('pallets', $r['pallets'])."</td>";
		$c .= "<td class='label_class'>Length (inches):</td><td>".$this->fetch_edit('length', $r['length'])."</td>";
		$c .= "<td class='label_class'>Size:</td><td>".$this->fetch_edit('size', $r['size'])."</td></tr>";
		$c .= "<tr><td class='label_class'>Load Type:</td><td>".$this->fetch_edit('load_type', $r['load_type'])."</td>";
		$c .= "<td class='label_class'>Commodity:</td><td>".$this->fetch_edit('commodity', $r['commodity'])."</td>";
		$c .= "<td class='label_class'>Weight (lbs.):</td><td>".$this->fetch_edit('weight', $r['weight'])."</td>";
		$c .= "<td class='label_class'>Class:</td><td>".$this->fetch_edit('class', $r['class'])."</td>";
		
		$c .= "</tr></table>";
		$c .= "		</div>";
		$c .= "</fieldset>";
		//================
		$c .= "</td></tr><tr><td colspan=3 class='bottom_pad'>";
		//=== Warehouse =======
		$c .= "
		<fieldset>
				<legend>";
		if($auth_edit){
			$c .= "<a href='#' onclick=\"javascript:popUp('?page=$this->page&load_id=$this->load_id&action=$this->warehouse_search_edit&customer_id=$r[customer_id]&".SMALL_VIEW."', 700, 550);return false;\">Warehouse</a>";
		}else{
			$c .= 'Warehouse';
		}
		$c .= "</legend>";
		$c .= "<div id='load_warehouse_portal'></div>";
		$c .= "</fieldset>";
		$c .= $this->script("		
			function add_load_warehouse(load_id){
				var warehouse_selection = document.getElementById('warehouse_selection');
				var type = document.getElementById('warehouse_type');
				var param_str = 'table=load_warehouse&type='+type.value+'&warehouse_id='+warehouse_selection.value+'&load_id=';
				var obj=new Object();
				obj.id = param_str;
				obj.value = load_id;
				db_save(obj);
				update_warehouse_portal();
			}
			
			function update_warehouse_portal(){
				get_portal('load_warehouse', 'load_id=$this->load_id');
				init_cals();
			}
			
			function delete_warehouse(warehouse_id){
				if(confirm('Are you sure you want to delete warehouse '+warehouse_id+' from this load?')){
					var obj=new Object();
					obj.id = 'action=$this->delete&table=load_warehouse&load_id=$this->load_id&warehouse_id=';
					obj.value = warehouse_id;
					db_save(obj);
					update_warehouse_portal();
				}
			}
			var cal_act_dates = Array();
			function init_cals(){
				include('./CalendarPopup.js');
				
				var cals = document.getElementsByName('cal_act_date');
				for(i=0;i<cals.length;i++){
					cal_act_dates[i] = new CalendarPopup(cals[i].id);
					cal_act_dates[i].id = cals[i].id+'_cal';
				}
			}
			jQuery().ready(function(){
				update_warehouse_portal();
			});
			");
		
		
		//==================
		$c .= "</td></tr><tr><td colspan=3 class='bottom_pad'>";
		//====Money====
		$c .= "
		<fieldset>
				<legend>";
		if(!$this->been_delivered() || Auth::loggedInAs('admin')){
			$c .= "<a href='#' onclick=\"javascript:popUp('?page=$this->page&action=$this->edit_str&module=$this->money_str&load_id=$this->load_id&".SMALL_VIEW."',0,1064,240);return false;\">Money</a>";
		}else{
			$c .= 'Money';
		}
		$c .= "</legend><div id='money_module'></div>";
		$c .= "</fieldset>";
		$c .= $this->script("
					
					var gp=0,
						wcp=0,
						dlsp=0;
						
					function wc_change(o){
						var o = document.getElementsByName('wc_active')[0];
						var gp_el = document.getElementById('gp');
						var wc_perc_el = document.getElementById('wc_percent');
						var wcp_el = document.getElementById('wcp');
						var dtsp_el = document.getElementById('dtsp');
						
						if(o && o.checked){
							var gp_el = document.getElementById('gp');
							gp = gp_el.innerHTML;
							
							var wc_perc = wc_perc_el.innerHTML * .01;
							
							wcp = Math.round(gp * wc_perc *100)/100;
							wcp_el.style.visibility = 'visible';
							wcp_el.innerHTML = wcp;
							
							
						}else{
							wcp = 0;
							wcp_el.style.visibility = 'hidden';
						}
						setDtsp();
					}
					
					function dls_change(o){
						var o = document.getElementsByName('dls_active')[0];
						var gp_el = document.getElementById('gp');
						var dls_perc_el = document.getElementById('dls_percent');
						var dlsp_el = document.getElementById('dlsp');
						var dtsp_el = document.getElementById('dtsp');
						
						if(o && o.checked){
							var gp_el = document.getElementById('gp');
							gp = gp_el.innerHTML;
							
							var dls_perc = dls_perc_el.innerHTML * .01;
							
							dlsp = Math.round(gp * dls_perc *100)/100;
							dlsp_el.style.visibility = 'visible';
						}else{
							dlsp = 0;
							dlsp_el.style.visibility = 'hidden';
						}
						setDtsp();
					}
					
					function setDtsp(){
						jQuery('#dlsp').text(dlsp);
						var gp_val = parseInt(gp) - (parseInt(wcp) + parseInt(dlsp));
						jQuery('#dtsp').text(gp_val).css('visibility', 'visible');
					}
					
					function update_money_module(){
						get_module('$this->money_str', 'action=view&load_id=$this->load_id');
						update_gp();
					}
					jQuery().ready(function(){
						update_money_module();
					});
					");
		//==================
		$c .= "</td></tr><tr><td colspan=3>";
		//====Carriers====
		$c .= "
		<fieldset>
				<legend>";
			$c .= "<a href='#' onclick=\"javascript:popUp('?page=$this->page&load_id=$this->load_id&action=$this->carrier_search_edit&".SMALL_VIEW."','carrier_search_'+$this->load_id, 700, 550);return false;\">Carrier</a>";
		$c .= "</legend><div id='load_carrier_portal'></div>";
		$c .= "</fieldset>";
		$c .= $this->script("
					function cancel(){
						alert(this.value);
						var c = document.getElementById('content');
						c.style.color = '$this->cancel_color';
					}
					
                                         function add_load_carrier(load_id){
						var carrier_selection = document.getElementById('carrier_selection');
						var param_str = 'table=load_carrier&carrier_id='+carrier_selection.value+'&load_id=';
						var obj=new Object();
						obj.id = param_str;
						obj.value = load_id;
						db_save(obj);
						update_carrier_portal();
					}
                                        
					function update_carrier_portal(){
						get_portal('load_carrier', 'load_id=$this->load_id');
					}
					
					function delete_carrier(carrier_id){
						if(confirm('Are you sure you want to delete carrier '+carrier_id+' from this load?')){
							var obj = {};
							obj.id = 'action=$this->delete&table=load_carrier&load_id=$this->load_id&carrier_id=';
							obj.value = carrier_id;
							db_save(obj);
							update_carrier_portal();
						}
					}
					jQuery().ready(function(){
						update_carrier_portal();
					});
					");
		
		//================
		$c .= "</td></tr><tr><td colspan=3>";
		
		
		$ltl_c = $this->get_column('ltl_carrier');
		$ltl_c->set_value_list($this->ltl_carriers);
		//====LTL info====
		$c .= "
		<fieldset>
				<legend>LTL Info</legend>
				<div id='ltl_info'>
					<table style='width:100%;border:1px solid black;' class='content'>
						<tr>
							
							<th>
								Carrier
							</th>
							<th>
								Pro #
							</th>
							<th>
								BOL #
							</td>
							<th>
								Bill Of Lading
							</td>
						</tr>
						<tr>
							
							<td>
								".$this->fetch_edit('ltl_carrier', $r['ltl_carrier'])."
							</td>
							<td>
								".$this->fetch_edit('pro_number', $r['pro_number'])."
							</td>
							<td>
								".$this->fetch_edit('ltl_number', $r['ltl_number'])."
							</td>
							<td>
								<input type=\"button\" value=\"Bill Of Lading\" onclick=\"javascript:window.open('?page=bol&load_id=$this->load_id&".SMALL_VIEW."', 'bol', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=730,height=980,left = 0,top = 0');\" />
							</td>
						</tr>
					</table>
				</div>
			</div>
		</fieldset>";
		
		//==================
		$c .= $this->script("
		function open_rate_conf(url, id){
                    eval(\"window.open(url, '\" + id + \"', 'toolbar=1, scrollbars=1, location=1, statusbar=1, menubar=1, resizable=1, width=700, height=600, left = 0, top = 0');\");
		}");
		$c .= "</td></tr></table>";
		
		return $c;
	}
	
	function create_new(){
            set_post('order_date', 'NOW()');
            set_post('order_by', Auth::getUserId());
            $this->add($_POST);
            if(DB::error()){
		echo DB::error();
            }
            $this->load_id = $this->last_id;
	}
	
	function get_new(){
		
		$c = $this->script("
				function submit_close(){
					var f = document.getElementById('new_form');
					f.submit();
				}
				function cancel_close(){
					window.close();
				}");
		$c .= '<center><table>';
		$c .= "<form id='new_form' onsubmit='submit_close();' method='post'>";
		$c .= "<input type='hidden' name='page' value='$this->page'>";
		$c .= "<input type='hidden' name='action' value='$this->add_str'>";
		
		$c .= '<tr><td>Rating</td><td>'.$this->fetch_new('rating').'</td></tr>';
		$c .= '<tr><td>Ordered</td><td>'.$this->fetch_new('ordered').'</td></tr>';
		$c .= '<tr><td>Customer</td><td>'.$this->fetch_new('customer_id', safe_get($_REQUEST['customer_id'])).'</td></tr>';
		$c .= '<tr><td>Customer Total</td><td>'.$this->fetch_new('customer_total').'</td></tr>';
		$c .= '<tr><td>Carrier Total</td><td>'.$this->fetch_new('carrier_total').'</td></tr>';
		$c .= '<tr><td>Trailer Type</td><td>'.$this->fetch_new('trailer_type').'</td></tr>';
		$c .= '<tr><td>Pallets</td><td>'.$this->fetch_new('pallets').'</td></tr>';
		$c .= '<tr><td>Length</td><td>'.$this->fetch_new('length').'</td></tr>';
		$c .= '<tr><td>Size</td><td>'.$this->fetch_new('size').'</td></tr>';
		$c .= '<tr><td>Weigth</td><td>'.$this->fetch_new('weight').'</td></tr>';
		$c .= '<tr><td>Class</td><td>'.$this->fetch_new('class').'</td></tr>';
		$c .= '<tr><td>Carrier</td><td>'.$this->fetch_new('carrier_id').'</td></tr>';
		$c .= '<tr><td>Order By</td><td>'.$this->fetch_new('order_by', safe_get(Auth::getUserId($_REQUEST[Auth::COOKIE_USERNAME]))).'</td></tr>';
		if(isset($_REQUEST[SMALL_VIEW])){
			$c .= '<tr><td><input type="button" onclick="submit_close()" value="$this->add_str"></td></tr>';
			$c .= '<tr><td><input type="button" onclick="cancel_close()" value="$this->cancel_str"></td></tr>';
		}else{
			$c .= "<tr><td><input type='submit' name='action' value='$this->add_str'></td></tr>";
			$c .= "<tr><td><input type='submit' name='action' value='$this->cancel_str'></td></tr>";
		}
		$c .= '</form></table></center>';
		
		return $c;
	}

	function get_customer($customer_id){
		$sql ="SELECT *, CONCAT('<a href=\"?page=customer&action=$this->edit_str&customer_id=',customer_id,'\">', name, '</a>') name FROM customer WHERE customer_id = $customer_id";
		$r = DB::query($sql);
		return DB::fetch_assoc($r);
	}
	
	function get_user($user_id){
		
		$sql ="SELECT * FROM `users` WHERE user_id = $user_id";
		$r = DB::query($sql);
		return DB::fetch_assoc($r);
	}
	
	function fetch_new($name, $value=null){
		$c = $this->get_column($name);
		$pk_obj = $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		
		$o = $c->get_edit_html($value);
		
		return $o->render();
	}
	
	function fetch_edit($name, $value=null, $protected=false){
		$c = $this->get_column($name);
                
		$pk_obj = $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		if($protected){
                    return "<div class='faux_edit'>".$c->get_view_html($value)."</div>";
		}else{
			if(isset($c)){
			$o = $c->get_edit_html($value);
			$o->set_id("action=$this->update&table=$this->name&load_id=$this->load_id&".$name."=");
			$o->add_attribute('onchange', 'db_save(this);return column_updated(this);');
			
			$script = $this->script("
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);");
		
		return $o->render();
		}
		}
	}
	
	function fetch_edit_lc($t, $c_id, $name, $value){
		$c = $t->get_column($name);
		$pk_obj = $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		
		$o = $c->get_edit_html($value);
		$o->set_id("action=$this->update&table=$t->name&load_id=$this->load_id&carrier_id=$c_id&$name=");
		$o->add_attribute('onchange', 'db_save(this);column_updated(this);');
		$script = $this->script("
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);");
		
		return $o->render();
	}
	
	function get_daily_profit($date, $filter=null){
		$sql = "
			SELECT
	round(sum(
		(
			(
				(cust_line_haul * cust_line_haul_amount) + 
				(cust_detention * cust_detention_amount) + 
				(cust_tonu * cust_tonu_amount) + 
				(cust_unload_load * cust_unload_load_amount) +
				(cust_fuel * cust_fuel_amount) + 
				(cust_other * cust_other_amount)
			)
			-
			(
				(carrier_line_haul * carrier_line_haul_amount) + 
				(carrier_detention * carrier_detention_amount) + 
				(carrier_tonu * carrier_tonu_amount) + 
				(carrier_unload_load * carrier_unload_load_amount) +
				(carrier_fuel * carrier_fuel_amount) + 
				(carrier_other * carrier_other_amount)
			)
		)
		*
		(IF(wc_active > 0, -(wc_percent * .01)+1, 1))
		)
	,2) profit
					FROM `load` l
					WHERE activity_date = '$date'
					AND load_id in (SELECT load_id FROM load_carrier)";
		if(isset($filter) && is_array($filter)){
			if(isset($filter['user_id'])){
				$sql .= " AND (l.order_by = $filter[user_id]
							OR l.customer_id in (SELECT customer_id 
												FROM customer 
												WHERE acct_owner = $filter[user_id]))";
			}elseif(isset($filter['region_id'])){
				$sql .= "	AND (l.order_by in (SELECT user_id
												FROM user_region_list
												WHERE region_list_id = $filter[region_id])
							OR l.customer_id in (SELECT customer_id 
												FROM customer 
												WHERE acct_owner in (SELECT user_id
																	FROM user_region_list
																	WHERE region_list_id = $filter[region_id])))";
			}
		}
		$result = DB::query($sql);
		if(DB::error()){
			echo DB::error();
			echo $sql;
		}
		$r = DB::fetch_assoc($result);
		
		return $r['profit'];
	}
	
	function get_zones(){
		$sql = "SELECT *
				FROM region_lists";
		$re = DB::query($sql);
		$a = Array('');
		while($r = DB::fetch_assoc($re)){
			$a[$r['id']] = $r['name'];
		}
		return $a;
	}
	
	function get_region_users(){
		$sql = "SELECT *
				FROM region_lists";
		$re = DB::query($sql);
		$t = new Template();
		$t->error_reporting = E_ALL & ~E_NOTICE;
		$rul = Array();
		while($rr = DB::fetch_assoc($re)){
			$new_row = Array();
			$new_row['name'] = $rr['name'];
			$new_row['region_id'] = $rr['id'];
			$rul[] = $new_row;
			$sql = "SELECT *
					FROM `users`
					WHERE user_id in (	SELECT user_id
										FROM user_region_list
										WHERE region_list_id = $rr[id])
					ORDER BY username";
										
			$ru = DB::query($sql);
			while($u = DB::fetch_assoc($ru)){
				$new_row = Array();
				$new_row['name'] = $u['username'];
				$new_row['user_id'] = $u['user_id'];
				$rul[] = $new_row;
			}
		}
		$t->assign('region_users', $rul);
	
		return $t->fetch(App::getTempDir().'region_user_list.tpl');
	}
	
	function load_board(){
		!isset($_GET['page']) ? $_GET['page'] = basename(__FILE__, '.php') : '';
		isset($_GET['day']) ? $cur_day = $_GET['day'] : $cur_day = date("d");
		isset($_GET['month']) ? $cur_month = $_GET['month'] : $cur_month = date("m");
		isset($_GET['year']) ? $cur_year = $_GET['year'] : $cur_year = date("Y");

		$cur_date_str = $cur_day . " " . $this->get_month_name($cur_month) . " " . $cur_year;
		$prev_date_str = $cur_date_str . " -1 day";
		$next_date_str = $cur_date_str . " +1 day";
		$db_date = $cur_year.'-'.$cur_month.'-'.$cur_day;
		$next_date = strtotime($next_date_str);
		$prev_date = strtotime($prev_date_str);
		$nav['prev_day'] = date("d", $prev_date);
		$nav['prev_month'] = date("m", $prev_date);
		$nav['prev_year'] = date("Y", $prev_date);
		
		$nav['next_day'] = date("d", $next_date);
		$nav['next_month'] = date("m", $next_date);
		$nav['next_year'] = date("Y", $next_date);
		
		$cal = new calendar();
		$cal->add_attrib('page', $this->page);
		
		$t = new Template();
		$t->error_reporting = E_ALL & ~E_NOTICE;
		if(Auth::loggedInAs('admin')){
			$t->assign('admin', true);
			$t->assign('daily_profit', $this->get_daily_profit($db_date, $_GET));
		}
		$t->assign('cal_navigator', $cal->get_navigator());
		$t->assign('nav', $nav);
		$t->assign('region_users', $this->get_region_users());
		$t->assign('ordered', $this->get_loads($db_date, $this->ordered, $_GET));
		$t->assign('booked', $this->get_loads($db_date, $this->booked, $_GET));
		$t->assign('loaded', $this->get_loads($db_date, $this->loaded, $_GET));
		$t->assign('delivered', $this->get_loads($db_date, $this->delivered, $_GET));
		return $t->fetch(App::getTempDir().'load_board.tpl');
	}
	
	function get_loads($date, $type, $filter=null){
		
		$q = new portal();
		$sql = "	SELECT 	load_id
							, load_type
							, cancelled
							, rating
							, RIGHT(l.load_id, 4) 'id'
							, IFNULL(
										(SELECT CONCAT(w.state,' ',w.city)
										FROM warehouse w, load_warehouse lw
										WHERE w.warehouse_id = lw.warehouse_id
										AND lw.load_id = l.load_id
										AND lw.type = 'PICK'
										ORDER BY lw.activity_date ASC
										LIMIT 1) 
								, '$this->null_str') origin,
								IFNULL(
										(SELECT CONCAT(w.state,' ',w.city)
										FROM warehouse w, load_warehouse lw
										WHERE w.warehouse_id = lw.warehouse_id
										AND lw.load_id = l.load_id
										AND lw.type = 'DEST'
										ORDER BY lw.activity_date DESC
										limit 1) 
								, '$this->null_str') dest
							FROM `load` l
							WHERE l.activity_date = '$date'";
		if(isset($filter) && is_array($filter)){
			if(isset($filter['user_id'])){
				$sql .= " AND (l.order_by = $filter[user_id]
							OR l.customer_id in (SELECT customer_id 
												FROM customer 
												WHERE acct_owner = $filter[user_id]))";
			}elseif(isset($filter['region_id'])){
				$sql .= "AND l.zone = $filter[region_id]";
			}
		}
		$has_been_loaded = "	AND (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'PICK'
								AND complete = 1) > 0";
		
		$has_been_delivered ="	AND (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'DEST'
								AND complete = 1) > 0";
		$not_delivered = "	AND ( (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'DEST'
								AND complete != 1) > 0
						OR (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'DEST') = 0)";
		$not_loaded ="	AND ((	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'PICK'
								AND complete != 1) > 0
						OR (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'PICK') = 0)";
		$has_been_booked = "	AND (SELECT count(*) FROM load_carrier lc WHERE lc.load_id = l.load_id) > 0";
		//==== Delivered ====
		if($type == $this->delivered){
			//A carrier has been booked...
			$sql .= $has_been_booked;
			// has been loaded
			$sql .=	$has_been_loaded;
								
			// and has been delivered
			$sql .= $has_been_delivered;
		}
		
		//==== Loaded ====
		if($type == $this->loaded){
			//A carrier has been booked...
			$sql .= $has_been_booked;
			// has been loaded
			$sql .=	$has_been_loaded;
			
			//but NOT delivered 
			$sql .= $not_delivered;
		}
		
		if($type == $this->booked){
			//A carrier has been booked...
			$sql .= $has_been_booked;
			
			// AND NOT loaded
			$sql .=	$not_loaded;
			//OR NOT delivered 
			$sql .=  $not_delivered;
		}
		
		if($type == $this->ordered){
			//A carrier has NOT been booked...
			$sql .= "	AND (SELECT count(*) FROM load_carrier lc WHERE lc.load_id = l.load_id) = 0";
			
			// AND NOT loaded
			$sql .=	$not_loaded;
			//OR NOT delivered 
			$sql .= $not_delivered;
		}
		
		
		$re = DB::query($sql);
		$t = new Template();
		$t->error_reporting = E_ALL & ~E_NOTICE;
		$t->assign('loads', DB::to_array($re));
            $path = App::getTempDir().'load_list.tpl';
		$temp = $t->fetch($path);
		
		return $temp;
	}
	
	function get_month_name($dayInt){
		$monthAry = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		$dayInt = ltrim($dayInt, "0");
		return $monthAry[$dayInt];
	}
	
	function get_carriers_selection(){
		
		$sql = "SELECT * FROM carrier";
		$r = DB::query($sql);
		$select = new select_input("carrier_id", "carrier_id", "name", $r);
		$select->set_id("carrier_selection");
	
		return $select->render();
	}
	
	function get_warehouse_selection($customer_id){
		
		$sql = "SELECT * FROM warehouse w WHERE w.customer_id = $customer_id";
		$r = DB::query($sql);
		$select = new select_input("warehouse_id", "warehouse_id", "name", $r);
		$select->set_id("warehouse_selection");
	
		return $select->render();
	}
	
	function get_warehouse_type_select(){
		
		
		$select = new select_input("type", null, null, $this->warehouse_types);
		$select->set_id("warehouse_type");

		return $select->render();
	}
	
	function gp_script(){
		return $this->script("
			function column_updated(t){
				if (t.name == 'customer_total' || t.name == 'carrier_total'){
					update_gp();
				}else if(t.name == 'cancelled' || t.name == 'rating'){
					return set_content_color();
				}
			}
			
			function update_gp(){
				var gp = document.getElementById('gp');
				
				var cust_total_el = document.getElementById('customer_total');
				var car_total_el = document.getElementById('carrier_total');
				var cust_total = cust_total_el.innerHTML.valueOf();
				var car_total = car_total_el.innerHTML.valueOf();
				
				var gp_val = (cust_total - car_total).toFixed(2)||0;
				gp.innerHTML = gp_val;
				
				wc_change();
				dls_change();
			}
                        ");
	}
	
	function get_rate_conf_new(){
		$sql = "SELECT	l.load_id,
						l.class,
						l.commodity,
						l.trailer_type,
						lc.cell_number,
						lc.driver_name,
						lc.tractor_number,
						lc.trailer_number,
						c.carrier_id,
						origin_name,
						origin_address,
						origin_state,
						origin_city,
						origin_zip,
						origin_phone,
						origin_notes,
						pickup_date,
						pickup_time,
						dest_name,
						dest_address,
						dest_state,
						dest_city,
						dest_zip,
						dest_phone,
						dest_notes,
						delivery_date,
						delivery_time,
						pick_num,
						dest_num,
						ltl_number,
						l.weight,
						l.pro_number,
						l.size,
						l.pallets,
						l.carrier_line_haul,
						carrier_line_haul_amount line_haul_amount,
						(line_haul_amount * l.carrier_line_haul) total_line_haul
						carrier_detention,
						carrier_detention_amount detention_amount,
						carrier_tonu,
						carrier_tonu_amount tonu_amount,
						carrier_unload_load,
						carrier_unload_load_amount unload_load_amount,
						carrier_fuel,
						carrier_fuel_amount fuel_amount,
						carrier_other,
						carrier_other_amount other_amount,
						c.contact_name,
						c.name carrier_name,
						c.main_phone_number carrier_phone,
						c.fax carrier_fax,
						(SELECT CONCAT(u.first_name, ' ', u.last_name) FROM `users` u WHERE u.user_id=lc.booked_with) booked_name,
						(SELECT username FROM `users` WHERE user_id=lc.booked_with) booked_with,
						(SELECT username FROM `users`, customer c WHERE user_id = c.acct_owner AND c.customer_id = l.customer_id) booked_salesperson,
						pickup_date,
						c.phys_address carrier_address
				FROM `load` l, carrier c,  load_carrier lc,
				(SELECT lwp.load_id,
						name origin_name,
						address origin_address,
						state origin_state,
						city origin_city,
						zip origin_zip,
						phone origin_phone,
						notes origin_notes,
						pick_dest_num pick_num,
						DATE_FORMAT(lwp.activity_date, '$this->date_format') pickup_date,
						CONCAT(DATE_FORMAT(lwp.open_time, '%h:%i %p'), ' - ', DATE_FORMAT(lwp.close_time, '%h:%i %p')) pickup_time 
					FROM warehouse w, load_warehouse lwp
					WHERE lwp.type ='PICK'
					AND w.warehouse_id = lwp.warehouse_id) origin, 
				(SELECT lwd.load_id,
						name dest_name,
						address dest_address,
						state dest_state,
						city dest_city,
						zip dest_zip,
						phone dest_phone,
						notes dest_notes,
						pick_dest_num dest_num,
						DATE_FORMAT(lwd.activity_date, '$this->date_format') delivery_date,
						CONCAT(DATE_FORMAT(lwd.open_time, '%h:%i %p'), ' - ', DATE_FORMAT(lwd.close_time, '%h:%i %p')) delivery_time 
					FROM warehouse w, load_warehouse lwd
					WHERE lwd.type ='DEST' 
					AND w.warehouse_id = lwd.warehouse_id) dest
				WHERE l.load_id = $_REQUEST[load_id]
				AND lc.load_id = l.load_id
				AND c.carrier_id = lc.carrier_id
				AND origin.load_id = l.load_id
				AND dest.load_id = l.load_id
				";
		$res = DB::query($sql);
		if(DB::error()){
			echo $sql."<br>";
			echo DB::error();
		}
		$r = DB::fetch_assoc($res);
		
		$t = new Template();
		$t->error_reporting = E_ALL & ~E_NOTICE;
		$t->assign('load', $r);
		$t->assign('pick', DB::to_array($this->get_pickups($_GET['load_id'])));
		$t->assign('drop', DB::to_array($this->get_drops($_GET['load_id'])));
		return $t->fetch(App::getTempDir().'rate_conf.tpl');
	}
	
	function get_rate_conf(){
		$sql = "SELECT	l.load_id,
						l.class,
						l.commodity,
						l.trailer_type,
						lc.cell_number,
						lc.driver_name,
						lc.tractor_number,
						lc.trailer_number,
						c.carrier_id,
						origin_name,
						origin_address,
						origin_state,
						origin_city,
						origin_zip,
						origin_phone,
						origin_notes,
						pickup_date,
						pickup_time,
						dest_name,
						dest_address,
						dest_state,
						dest_city,
						dest_zip,
						dest_phone,
						dest_notes,
						delivery_date,
						delivery_time,
						pick_num,
						dest_num,
						ltl_number,
						l.weight,
						l.pro_number,
						l.size,
						l.pallets,
						l.carrier_line_haul,
						carrier_line_haul_amount line_haul_amount,
						carrier_detention,
						carrier_detention_amount detention_amount,
						carrier_tonu,
						carrier_tonu_amount tonu_amount,
						carrier_unload_load,
						carrier_unload_load_amount unload_load_amount,
						carrier_fuel,
						carrier_fuel_amount fuel_amount,
						carrier_other,
						carrier_other_amount other_amount,
						c.contact_name,
						c.name carrier_name,
						c.main_phone_number carrier_phone,
						c.fax carrier_fax,
						(SELECT CONCAT(u.first_name, ' ', u.last_name) FROM `users` u WHERE u.user_id=lc.booked_with) booked_name,
						(SELECT username FROM `users` WHERE user_id=lc.booked_with) booked_with,
						(SELECT username FROM `users`, customer c WHERE user_id = c.acct_owner AND c.customer_id = l.customer_id) booked_salesperson,
						pickup_date,
						c.phys_address carrier_address
				FROM `load` l, carrier c,  load_carrier lc,
				(SELECT lwp.load_id,
						name origin_name,
						address origin_address,
						state origin_state,
						city origin_city,
						zip origin_zip,
						phone origin_phone,
						notes origin_notes,
						pick_dest_num pick_num,
						creation_date,
						type,
						DATE_FORMAT(lwp.activity_date, '$this->date_format') pickup_date,
						CONCAT(DATE_FORMAT(lwp.open_time, '%h:%i %p'), ' - ', DATE_FORMAT(lwp.close_time, '%h:%i %p')) pickup_time 
					FROM warehouse w, load_warehouse lwp
					WHERE lwp.type ='PICK'
					AND w.warehouse_id = lwp.warehouse_id) origin, 
				(SELECT lwd.load_id,
						name dest_name,
						address dest_address,
						state dest_state,
						city dest_city,
						zip dest_zip,
						phone dest_phone,
						notes dest_notes,
						pick_dest_num dest_num,
						creation_date,
						type,
						DATE_FORMAT(lwd.activity_date, '$this->date_format') delivery_date,
						CONCAT(DATE_FORMAT(lwd.open_time, '%h:%i %p'), ' - ', DATE_FORMAT(lwd.close_time, '%h:%i %p')) delivery_time 
					FROM warehouse w, load_warehouse lwd
					WHERE lwd.type ='DEST' 
					AND w.warehouse_id = lwd.warehouse_id) dest
				WHERE l.load_id = $_REQUEST[load_id]
				AND lc.load_id = l.load_id
				AND c.carrier_id = lc.carrier_id
				AND origin.load_id = l.load_id
				AND dest.load_id = l.load_id
				";
		$res = DB::query($sql);
		if(DB::error()){
			echo $sql."<br>";
			echo DB::error();
		}
		$r = DB::fetch_assoc($res);
		$header ='';
		$header .= "<style media='print' type='text/css'>
			body{font-size:.9em}
			table{font-size:10pt;}
			#print_button{display:none}
		</style>";
		$header .= $this->style(".bold{font-weight:bold;}.heavy_frame{border:1px solid black}.right{text-align:right;}.center{text-align:center;}table{font-size:9pt;}
		.bottom_border{border-top:solid black 1px}")."<body style='font-size:.8em;width:7in;font-family:sans-serif;'>";
		
		$header .= "<center>
		<table width=100% border=0>
			<tr>
				<td style='text-align:left' width='1%'>
					<center>Domestic Transport Solutions Carrier Confimation Load #</center>
				</td>
			</tr>
			<tr>
				<td>
					<table border=0 width=100%>
						<tr>
							<td style='vertical-align:middle' width=1%>
								<img src='".App::getImgRoot()."/dts.gif'>
							</td>
							<td style='vertical-align:middle'>
							<center><div style='font-size:16pt;padding:3pt;width:4em;' class='bold heavy_frame'>$_REQUEST[load_id]</div>
							</td>
							<td width=30% style='vertical-align:middle'>";
		if($r['ltl_number']){
		$header .= "				
							
								<center><div style='padding:6pt;' class='bold heavy_frame'>$r[pro_number]</div><div style='padding:6pt;font-size:.8em' class='bold heavy_frame'>$r[ltl_number]
								</div>
							";
		}
		$header .= "
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
				<center>
					Driver must call Domestic Transport Solutions<br>
		For distpatch at 847-981-1400 and ask for load # $r[load_id]<br>Domestic Transport Solutions has 24 hour dispatch. Call anytime.</td></tr></table>";
		$header .= "<hr>";
		$gutter ='84px';
		$origin = "<table width='80%' border=0><tr>";
		$origin .= "<td colspan=5>Our Contract Carrier Agreement is amended as follows:</td>";
		$picks = $this->get_pickups($_REQUEST['load_id']);
		while($pick = DB::fetch_assoc($picks)){
			
			$origin .= "</tr><tr>";
			$origin .= "<td>Shipper:</td><td class='bold'>$pick[origin_name]</td>";
			$origin .= "<td width='$gutter'></td>";
			$origin .= "<td>Pick&nbsp;Up&nbsp;Date:</td><td class='bold'>$pick[pickup_date]</td>";
			$origin .= "</tr><tr>";
			$origin .= "<td rowspan=2>Address:</td><td  rowspan=2 class='bold'>$pick[origin_address]<br>$pick[origin_city] $pick[origin_state] $pick[origin_zip]</td>";
			$origin .= "<td width='$gutter'></td>";
			$origin .= "<td>Pick Up Time:</td><td class='bold'>".nbsp($pick['pickup_time'])."</td>";
		
			$origin .= "</tr><tr>";
			$origin .= "<td width='$gutter'></td>";
			$origin .= "<td>Pick #:</td><td class='bold'>$pick[pick_num]</td>";
			$origin .= "</tr><tr>";
			$origin .= "<td>Phone:</td><td class='bold'>$pick[origin_phone]</td>";
			$origin .= "<td width='$gutter'></td>";
			$origin .= "<td>Notes:</td><td class= bold'>$pick[origin_notes]</td>";
			$origin .= "</tr><tr>";
			$origin .= "<td colspan=5><hr></td>";
			$origin .= "</tr><tr>";
		}
		$drops = $this->get_drops($_REQUEST['load_id']);
		while($drop = DB::fetch_assoc($drops)){
			$dest .= "<td>CONS:</td><td class='bold'>$drop[dest_name]</td>";
			$dest .= "<td width='$gutter'></td>";
			$dest .= "<td>Delivery Date:</td><td class='bold'>$drop[delivery_date]</td>";
			$dest .= "</tr><tr>";
			$dest .= "<td rowspan=2>Address:</td><td rowspan=2 class='bold'>$drop[dest_address]<br>$drop[dest_city] $drop[dest_state] $drop[dest_zip]</td>";
			$dest .= "<td width='$gutter'></td>";
			$dest .= "<td>Delivery&nbsp;Time:</td><td class='bold'>".nbsp($drop['delivery_time'])."</td>";
			$dest .= "</tr><tr>";
			$dest .= "<td width='$gutter'></td>";
			$dest .= "<td>Dest #:</td><td class='bold'>$drop[dest_num]</td>";
			$dest .= "</tr><tr>";
			$dest .= "<td>Phone:</td><td class='bold'>$drop[dest_phone]</td>";
			$dest .= "<td width='$gutter'></td>";
			$dest .= "<td>Notes:</td><td class='bold'>$drop[dest_notes]</td>";
			$dest .= "</tr></table></center>";
			$dest .= "<hr>";
		}
		$body = "Driver must ask for and receive:";
		$body .= "<center><table width='80%'><tr>";
		$body .= "<td>Commodity</td><td>Est Weight</td><td>Size</td><td>Class</td><td>Pallets</td><td>";
		$body .= "</tr><tr>";
		$body .= "<td class='bold heavy_frame'>$r[commodity]</td><td class='bold heavy_frame'>$r[weight]</td><td class='bold heavy_frame'>$r[size]</td><td class='bold heavy_frame'>$r[class]</td><td class='bold heavy_frame'>$r[pallets]</td><td>";
		$body .= "</tr></table></center><br><br>";
		$body .= "Any loading or unloading fees must be negotiated prior to invoicing and driver must get bill signed and obtain a lumper receipt. ALL DRIVERS MUST CALL IN FOR DISPATCH , WHEN LOADED AND EMPTY WITH A VERBAL POD TO INSURE NO PENALTIES. DRIVERS MUST CHECK CALL DAILY WITH DTS BETWEEN THE HOURS OF 7:00AM AND 10:00AM CENTRAL TIME. FAILURE TO MEET ANY OF THE ABOVE REQUIREMENTS WILL RESULT IN A $50.00 PENALTY PER INSTANCE.";
		$body .= "<br><br><center><table width='60%' class='' cellspacing='0'>";
		$body .= "<tr>
					<td></td>
					<td>Amount</td>
					<td>Rate</td>
					<td>Extended</td>
				</tr>";
		$line_haul_total = $this->money($r['line_haul_amount'] * $r['carrier_line_haul']);
		//top right bottom left
		if($line_haul_total > 0){
			$body .= "<tbody style='border:1px solid black'>
					<tr style=''>
						<td class='bold'>Line Haul</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['line_haul_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_line_haul'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$line_haul_total</td>
					</tr>";
		}
		$detention_total = $this->money($r['detention_amount']*$r['carrier_detention']);
		if($detention_total > 0){
			$body .= "	<tr style=''>
						<td class='bold '>Detention</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['detention_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_detention'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$detention_total</td>
					</tr>";
		}
		$tonu_total = $this->money($r['tonu_amount']*$r['carrier_tonu']);
		if($tonu_total > 0){
			$body .= "	<tr style=''>
						<td class='bold '>TONU</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['tonu_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_tonu'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$tonu_total</td>
					</tr>";
		}
		$unload_load_total = $this->money($r['unload_load_amount']*$r['carrier_unload_load']);
		if($unload_load_total > 0){
			$body .= "	<tr style=''>
						<td class='bold '>Unload/Load</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['unload_load_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_unload_load'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$unload_load_total</td>
					</tr>";
		}
		$fuel_total = $this->money($r['fuel_amount']*$r['carrier_fuel']);
		if($fuel_total > 0){
			$body .= "	<tr style=''>
						<td class='bold '>Fuel</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['fuel_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_fuel'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$fuel_total</td>
					</tr>";
		}
		$other_total = $this->money($r['other_amount']*$r['carrier_other']);
		if($other_total > 0){
			$body .= "	<tr style=''>
						<td class='bold '>Other</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['other_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 0px 0px 1px 1px;'>$".$this->money($r['carrier_other'])." </td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$other_total</td>
					</tr>";
		}
		$grand_total = $this->money($line_haul_total + $detention_total + $tonu_total + $unload_load_total + $fuel_total + $other_total);
		$body .= "	<tr style=''>
						<td class=''></td>
						<td class=''></td>
						<td class='bold' style='border: solid black;border-width: 0px 0px 1px 1px;'>TOTAL</td>
						<td class='bold right' style='border: solid black;border-width: 0px 1px 1px 1px;'>$$grand_total</td>
					</tr>
				</tbody><br><br>";
				
		$carrier = "<table width='80%'><tr>";
		$carrier .= "<td>Attention:</td><td class='bold'>$r[contact_name]</td>";
		$carrier .= "</tr><tr>";
		$carrier .= "<td>Carrier ID:</td><td class='bold'>S$r[carrier_id]</td>";
		$carrier .= "<td>Equipment:</td><td class='bold'>$r[trailer_type]</td>";
		$carrier .= "</tr><tr>";
		$carrier .= "<td>Carrier:</td><td class='bold'>$r[carrier_name]</td>";
		$carrier .= "<td>Booked With:</td><td class='bold'>$r[booked_with]</td>";
		$carrier .= "</tr><tr>";
		$carrier .= "<td>Phone:</td><td class='bold'>$r[carrier_phone]</td>";
		$carrier .= "<td>Booked Sales:</td><td class='bold'>$r[booked_salesperson]</td>";
		$carrier .= "</tr><tr>";
		$carrier .= "<td>Fax:</td><td class='bold'>$r[carrier_fax]</td>";
		$carrier .= "<td class='bold heavy_frame'>Driver Name:</td><td class='bold heavy_frame'>$r[driver_name]</td>";
		$carrier .= "</tr><tr>";
		$carrier .= "<td>Driver Cell:</td><td class='bold'>$r[cell_number]</td>";
		$carrier .= "<td class='bold heavy_frame'>Trac/Trail#:</td><td class='bold heavy_frame'>$r[tractor_number]</td><td class='bold heavy_frame'>$r[trailer_number]</td>";
		$carrier .= "</tr></table>";
		$legal = "<br><br><br><div style='text-align:left;font-size:.7em'>***Failure to notify Domestic Transport Solutions of inability to meet requested delivery time will result in settlement being held pending payment by customer. Any changes from service failure on part of the carrier are the repsonsibility of the carrier. If any extra charges occur, DTS must be notified immediately to receive approval for re-imbursement. Domstic Transport Solutions will not pay any additional charges w/o previous apporval by our office and written acknowledgement of the services on the original Bill of Lading. Domestic Transport Solutions payment terms are 30 days from receipt of invoice, original proof of delivery and a copy of the signed rate confirmation.</span>";
		$footer = "<table width='100%' border=0>
					<tr>
						<td width='50%' valign='top'>
							<div style='height:20px;border-bottom:1px solid black;margin:3px'></div>
							Carrier Representative Signature/Date
						</td>
						<td rowspan=2 style='font-size:.9em'>Thank You,<br>$r[booked_name]<br>Domestic Transport Solutions<br>847-981-1400 phone<br>847-981-1411 fax<br>2420 E. Oakton St Unit C<br>Elk Grove Township, IL 60005</td></tr>";
		$footer .= "<tr>
						<td valign='bottom'>
							<div style='height:20px;border-bottom:1px solid black;margin:3px'></div>
							Print Name And Title
						</td>
					</tr></table>";
		$footer .= "<input type='button' id='print_button' value='Print' onclick='print();'";
		return $header.$origin.$dest.$body.$carrier.$legal.$footer;
		
	}
	
	function get_order_by_name(){
		$sql = 'SELECT username FROM `users` u, `load` l WHERE u.user_id = l.order_by AND l.load_id = '.$this->load_id;
		$re = DB::query($sql);
		$ro = DB::fetch_array($re);
		return $ro[0];
	}
	
	function get_pickups($load_id){
		$sql = "SELECT lwp.load_id,
						name origin_name,
						address origin_address,
						state origin_state,
						city origin_city,
						zip origin_zip,
						phone origin_phone,
						notes origin_notes,
						pick_dest_num pick_num,
						DATE_FORMAT(lwp.activity_date, '$this->date_format') pickup_date,
						CONCAT(DATE_FORMAT(lwp.open_time, '%h:%i %p'), ' - ', DATE_FORMAT(lwp.close_time, '%h:%i %p')) pickup_time 
					FROM warehouse w, load_warehouse lwp
					WHERE lwp.type ='PICK'
					AND w.warehouse_id = lwp.warehouse_id
					AND lwp.load_id = $load_id
					ORDER BY creation_date";
		return DB::query($sql);
	}
	
	function get_drops($load_id){
		$sql = "SELECT lwp.load_id,
						name dest_name,
						address dest_address,
						state dest_state,
						city dest_city,
						zip dest_zip,
						phone dest_phone,
						notes dest_notes,
						pick_dest_num dest_num,
						DATE_FORMAT(lwp.activity_date, '$this->date_format') delivery_date,
						CONCAT(DATE_FORMAT(lwp.open_time, '%h:%i %p'), ' - ', DATE_FORMAT(lwp.close_time, '%h:%i %p')) delivery_time 
					FROM warehouse w, load_warehouse lwp
					WHERE lwp.type ='DEST'
					AND w.warehouse_id = lwp.warehouse_id
					AND lwp.load_id = $load_id
					ORDER BY creation_date";
		return DB::query($sql);
	}
	
	function get_acct_owner_name(){
		$sql = 'SELECT username
				FROM `users` u, customer c, `load` l
				WHERE u.user_id = c.acct_owner
				AND c.customer_id = l.customer_id
				AND l.load_id = '.$this->load_id;
		$re = DB::query($sql);
		$ro = DB::fetch_array($re);
		return $ro[0];
	}
	
	function been_delivered(){
		$sql = "SELECT count(*)
				FROM `load` l
				WHERE load_id = $this->load_id
				AND (	SELECT count(*)
						FROM load_warehouse lw
						WHERE lw.load_id = l.load_id
						AND lw.type = 'PICK'
						AND complete = 1) > 0
				AND (	SELECT count(*)
						FROM load_warehouse lw
						WHERE lw.load_id = l.load_id
						AND lw.type = 'DEST'
						AND complete = 1) > 0
				AND (	SELECT count(*)
						FROM load_carrier lc
						WHERE lc.load_id = l.load_id) > 0";
		$re = DB::query($sql);
		$ro = DB::fetch_array($re);
		return $ro[0];
	}
}
$l = new load_table();
	echo $l->render();
?>