<?php
require_once "includes/dts_table.php";

class load_table extends dts_table
{
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
	
	function load_table()
	{
		$this->dts_table('load');
		isset($_REQUEST['page']) ? $this->page = $_REQUEST['page'] : $this->page = 'load';
		
		if(isset($_REQUEST['load_id']))
		{
			$this->load_id = $_REQUEST['load_id'];
			$this->current_row();
		}
		
		
		$this->hide_delete();
		$this->hide_column('load_id');
		
		require_once"includes/hidden_input.php";
		$i = new hidden_input('page', $this->page);
		$this->add_other_inputs($i);
		$this->add_table_params('page', $this->page);
		
		$col =& $this->get_column('rating');

		$col->set_value_list($this->rating_list);//????
		
		$ob =& $this->get_column('customer_id');
		$ob->set_parent_label_column('name');
		
		$ob =& $this->get_column('order_by');
		$ob->set_parent_label_column('username');
		
		$ob =& $this->get_column('carrier_id');
		$ob->set_parent_label_column('name');
		
		$col =& $this->get_column('trailer_type');
		$col->set_value_list($this->trailer_type_list);
		
		$col =& $this->get_column('class');
		$col->set_value_list($this->load_classes);
		
		
		
		$this->tab_menu->add_tab("http://".HTTP_ROOT."/?page=$this->page&action=$this->search_edit", $this->search);
		$this->tab_menu->add_tab("http://".HTTP_ROOT."/?page=$this->page", 'Boards');
		$this->tab_menu->add_tab("http://".HTTP_ROOT."/?page=$this->page&action=$this->all", 'List');
		
	}
	
	function current_row()
	{
		if(isset($this->current_row))
		{
			return $this->current_row;
		}else{
			$this->current_row = $this->get_row($this->load_id);
		}
	}
	
	function render()
	{
		$code = '';
		if(logged_in())//1
		{
			if(isset($_REQUEST[$this->portal]))
			{
				switch(safe_get($_REQUEST[$this->portal]))
				{
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
				if(!isset($_REQUEST[SMALL_VIEW]))
				{
					$code .= $this->tab_menu->render();
					$code .= "<div class='tab_sep'></div>";
				}
				
				$code .= "<title>".SITE_NAME."-Loads</title>";
				$code .= $this->db_script();
				$code .= $this->portal_script();
				$code .= $this->module_script();
				$code .= $this->sortable_script();
				$code .= $this->popup_script();
				
				$code .= "<div class='content load_content' id='content'>";
				
				switch(get_action())
				{//3
					case $this->add_str:
						if($this->add())
						{
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
						//$code .= $this->check_size();
						break;
					case $this->customer_search_edit:
						$code .= $this->get_customer_search_edit_module();
						$code .= "<input type='button' onclick='window.close();' value='Close'>";
						//$code .= $this->check_size();
						break;
					case $this->view_str:
						switch (safe_get($_REQUEST[$this->mod_str]))
						{
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
								$code .= $this->get_load_edit();
								$code .= $this->gp_script();
								break;
						}
						break;
					case $this->edit_str:
						switch (safe_get($_REQUEST[$this->mod_str]))
						{
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
								$code .= $this->get_load_edit();
								$code .= $this->gp_script();
								break;
						}
						break;
					case $this->add_str:
						$code .= $this->script("
						function refresh_close()
						{
							window.opener.get_portal('load', 'load_id=$this->load_id');
							window.close();
						}
						window.onload = refresh_close;");
						$this->add();
						break;
					case $this->repeat:
						$this->repeat_load();
						header("location: http://".HTTP_ROOT."/?page=$this->page&action=$this->view_str&load_id=$this->load_id");
						break;
					case $this->new_str:
						$this->create_new();
						if($this->load_id)
						{
							header("location: http://".HTTP_ROOT."/?page=$this->page&action=$this->view_str&load_id=$this->load_id");
						}else{
							echo db_error();
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
	function repeat_load()
	{
		
		$old_load_id = $_REQUEST['load_id'];
		$sql = "INSERT INTO `load`(customer_id,trailer_type,pallets,length,size,weight,class,commodity, order_date, order_by)
				SELECT customer_id,trailer_type,pallets,length,size,weight,class,commodity, NOW(), ".get_user_id()." FROM `load` WHERE load_id = $old_load_id";
		$r = db_query($sql);
		if(db_error())
		{
			echo db_error();
			$this->add_error();
			$this->add_error($sql);
		}else
		{
			$this->load_id = db_insertid();
		}
		
		$sql = "INSERT INTO `load_warehouse`(load_id,open_time,close_time,warehouse_id,activity_date,activity_time,type,scheduled_with,creation_date)
				SELECT $this->load_id,open_time,close_time,warehouse_id,activity_date,activity_time,type,scheduled_with,NOW() FROM `load_warehouse` WHERE load_id = $old_load_id";
		$r = db_query($sql);
		if(db_error())
		{
			echo db_error();
			$this->add_error();
			$this->add_error($sql);
		}
	}
	function get_all_loads()
	{
		require_once"includes/portal.php";
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
	
	
	function get_search_results()
	{
		require_once("includes/portal.php");
		$c ='';
		
		$c .= "Load Search Results<br>";
		
		
		$sql = "SELECT 	l.load_id,
								IFNULL(
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
		
		if(isset($_REQUEST['load_id']) && intval($_REQUEST['load_id']) > 0)
		{
			$where .= " $clause load_id = ".intval($_REQUEST['load_id']);
		}elseif(isset($_REQUEST['order_number']) && $_REQUEST['order_number'] != '')
		{
			$where .= " $clause load_id in (SELECT load_id FROM load_warehouse WHERE pick_dest_num like '$_REQUEST[order_number]')";
		}
		$sql .= $where;
		//echo $sql;
		$p = new portal($sql);
		$p->set_table("load");
		$p->set_primary_key("load_id");
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		$c .= $p->render();
		
		return $c;
	}
	
	function get_search_edit()
	{
		require_once("includes/submit_input.php");
		$si = new submit_input($this->search, $this->action);
		$this->set_submit_input($si);
		
		$f =& $this->get_form();
		$f->set_get();
		$this->wilcard_search=true;
		
		$this->omit_all_columns();
		//$this->unhide_column('load_id');
		//$this->insert_column('load_id');
		$f->create_text_input('load_id');
		$f->create_text_input('order_number');
		
		return $this->_render_edit();
	}
	function get_carriers()
	{
		$c ='';
		
		$sql = "SELECT	c.carrier_id, phys_address,
						CONCAT('<a href=\"#\" onclick=\"javascript:popUp(\'http://".HTTP_ROOT."/?page=load_carrier&action=$this->edit_str&load_id=$this->load_id&carrier_id=',c.carrier_id,'&".SMALL_VIEW."\', \'load_carrier_".$this->load_id."_',c.carrier_id,'\', 960, 350)\">',name,'</a>') name,
						CONCAT('<center><input type=\"button\" value=\"Rate Conf\" onclick=\"javascript:open_rate_conf(\'http://".HTTP_ROOT."/?page=$this->page&portal=$this->rate_conf_str&load_id=$this->load_id&carrier_id=',c.carrier_id,'&".SMALL_VIEW."\', \'load_carrier_".$this->load_id."_',c.carrier_id,'\')\">') rate_conf_button ";

		
		if(logged_in_as('admin'))
		{
			$sql .= " ,CONCAT(	'<a href=\"#\" onclick=\"delete_carrier(',
							c.carrier_id,
							')\">$this->delete_icon</a>') `delete`";
		}
		$sql .= ",lc.carrier_id, CONCAT(main_phone_number, '<br>', fax) phone_fax,  (SELECT username FROM users u WHERE u.user_id =lc.booked_with) booked_with, lc.notes
					FROM `load` l, carrier c, load_carrier lc
					WHERE l.load_id = $this->load_id
					AND lc.load_id = l.load_id
					AND c.carrier_id = lc.carrier_id";
		$re = db_query($sql);
		$r = db_fetch_assoc($re);
		
		$c .= "<table style='width:100%;border:1px solid black;' class='content'><tr>\n";
		$c .= "<th>Carrier Name</td>\n";
		$c .= "<th>Address</td>\n";
		$c .= "<th>Phone<br>Fax</td>\n";
		$c .= "<th>Booked With</td>\n";
		$c .= "<th>Rate Confirmation</td>\n";
		if(logged_in_as('admin'))
		{
			$c .= "<th>Delete</td>\n";
		}
		$c .= "</tr><tr>";
		$c .= "<td class='faux_edit'>$r[name]</td>\n";
		$c .= "<td class='faux_edit'>$r[phys_address]</td>\n";
		$c .= "<td class='faux_edit'>$r[phone_fax]</td>\n";
		$c .= "<td class='faux_edit'>$r[booked_with]</td>\n";
		$c .= "<td class=''>$r[rate_conf_button]</td>\n";
		if(logged_in_as('admin'))
		{
			$c .= "<td class='border'>$r[delete]</td>\n";
		}
		$c .= "</tr></table>\n";
		
		return $c;
	}
	
	
	function get_warehouses()
	{
		//Needs work
		require_once"includes/portal.php";
		
		$sql = "SELECT	w.warehouse_id,
						lw.type,
						
						DATE_FORMAT(open_time, '$this->time_format') open_time,
						DATE_FORMAT(close_time, '$this->time_format') close_time,";
		
		
		
		$act_col = "CONCAT('<INPUT TYPE=\"text\" id=\"action=update&table=load_warehouse&load_id=',load_id,'&warehouse_id=',w.warehouse_id,'&activity_date=\" name=\"activity_date\" value=\"',IFNULL(DATE_FORMAT(activity_date,'$this->date_format'), '$this->blank_date'),'\" SIZE=10  readOnly=true onchange=\"db_save(this);column_updated(this);\"><img onClick=\"var e=document.getElementById(\'action=update&table=load_warehouse&load_id=',load_id,'&warehouse_id=',w.warehouse_id,'&activity_date=\');var c_str=\'cal_div_act_date_',w.warehouse_id,'_cal\';cal_act_date_',w.warehouse_id,'.select(e,\'cal_button_act_date_',w.warehouse_id,'\',\'MM/dd/yyyy\');\" ID=\"cal_button_act_date_',w.warehouse_id,'\" src=\"images/cal.gif\" onload=\"include(\'./CalendarPopup.js\');cal_act_date_',w.warehouse_id,' = new CalendarPopup(\'cal_div_act_date_',w.warehouse_id,'\');\" style=\"vertical-align:middle\"><span ID=\"cal_div_act_date_',w.warehouse_id,'\" style=\"background-color:white;position:absolute;z-index:1000\" name=\"cal_act_date\"></span>') act_date,";
		
		$sql .= $act_col;
		
		$complete = "CONCAT(	'<INPUT TYPE=\"checkbox\" id=\"action=update&table=load_warehouse&warehouse_id=',
								w.warehouse_id,
								'&load_id=',
								load_id,
								'&complete=\" name=\"complete\" onchange=\"db_save(this);column_updated(this);update_warehouse_portal();\"',
								IF(lw.complete = 1, 'checked', 'unchecked'),
								'>') complete,";
		$sql .= $complete;
		$name_col = "
											CONCAT('<a href=\"#\" onclick=\"javascript:popUp(\'http://".HTTP_ROOT."/?page=load_warehouse&action=$this->edit_str&load_id=$this->load_id&warehouse_id=',w.warehouse_id,'&".SMALL_VIEW."\', \'load_warehouse_".$this->load_id."_',w.warehouse_id,'\', 550, 550)\">',name,'</a>') name,";
		$sql .= $name_col;
		$sql .= "									 pick_dest_num,w.city, w.state ";
		$delete = '';
		if(logged_in_as('admin'))
		{
			$delete = " ,CONCAT(	'<a href=\"#\" onclick=\"delete_warehouse(',
							w.warehouse_id,
							')\">$this->delete_icon</div>') `delete`";
		}
		$sql .= $delete;
		$sql .= "						FROM load_warehouse lw, warehouse w 
								WHERE load_id = $this->load_id
								AND lw.warehouse_id = w.warehouse_id
								ORDER BY type desc, activity_date, open_time";
		//echo $sql;
		$c = $this->style("
		#load_warehouse_portal .tableContainer{height:99% !important;}
		#load_warehouse_portal table>tbody{height:3em !important;}");
		$p = new portal($sql);
		//print_r($this->times);
		$p->set_value_list('open_time', $this->times);
		$p->set_edit_column('pick_dest_num');
		$p->set_element_id('pick_dest_num', "\"action=".$this->update."&table=load_warehouse&warehouse_id=\$id&load_id=".$this->load_id."&pick_dest_num=\";");
		$p->set_element_on_change('pick_dest_num', "db_save(this);return column_updated(this);");
		
		$p->set_element_id('open_time', "\"action=".$this->update."&table=load_warehouse&warehouse_id=\$id&load_id=".$this->load_id."&open_time=\";");
		$p->set_element_on_change('open_time', "db_save(this);return column_updated(this);");
		
		$p->set_value_list('close_time', $this->times);
		$p->set_element_id('close_time', "\"action=".$this->update."&table=load_warehouse&warehouse_id=\$id&load_id=".$this->load_id."&close_time=\";");
		$p->set_element_on_change('close_time', "db_save(this);return column_updated(this);");
		
		$p->set_table($this->load_warehouse);
		
		$p->hide_column('warehouse_id');
		$p->set_primary_key('warehouse_id');
		
		$c .= $p->render();
		return $c;
	}
	
	function get_time_drop_down()
	{
		$c = "CONCAT('<select  id=\"action=update&table=load_warehouse&warehouse_id=',w.warehouse_id,'&load_id=', load_id, '&close_time=\" name=\"close_time\" value=\"',TIME_FORMAT(close_time, '%l:%i %p'),'\"  onchange=\"db_save(this);column_updated(this);update_warehouse_portal();\"></select>') close_time,";
	}
	
	function get_money_edit_module()
	{
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
				function column_updated(o)
				{
					if(o.name == 'cust_line_haul_amount' || o.name == 'cust_line_haul')
					{
						update_ext('cust_line_haul_extended', 'cust_line_haul_amount', 'cust_line_haul');
					}else if(o.name == 'carrier_line_haul_amount' || o.name == 'carrier_line_haul')
					{
						update_ext('carrier_line_haul_extended', 'carrier_line_haul_amount', 'carrier_line_haul');
					}else if(o.name == 'cust_detention_amount' || o.name == 'cust_detention')
					{
						update_ext('cust_detention_extended', 'cust_detention_amount', 'cust_detention');
					}else if(o.name == 'carrier_detention_amount' || o.name == 'carrier_detention')
					{
						update_ext('carrier_detention_extended', 'carrier_detention_amount', 'carrier_detention');
					}else if(o.name == 'cust_tonu_amount' || o.name == 'cust_tonu')
					{
						update_ext('cust_tonu_extended', 'cust_tonu_amount', 'cust_tonu');
					}else if(o.name == 'carrier_tonu_amount' || o.name == 'carrier_tonu')
					{
						update_ext('carrier_tonu_extended', 'carrier_tonu_amount', 'carrier_tonu');
					}else if(o.name == 'cust_unload_load_amount' || o.name == 'cust_unload_load')
					{
						update_ext('cust_unload_load_extended', 'cust_unload_load_amount', 'cust_unload_load');
					}else if(o.name == 'carrier_unload_load_amount' || o.name == 'carrier_unload_load')
					{
						update_ext('carrier_unload_load_extended', 'carrier_unload_load_amount', 'carrier_unload_load');
					}
					else if(o.name == 'cust_fuel_amount' || o.name == 'cust_fuel')
					{
						update_ext('cust_fuel_extended', 'cust_fuel_amount', 'cust_fuel');
					}else if(o.name == 'carrier_fuel_amount' || o.name == 'carrier_fuel')
					{
						update_ext('carrier_fuel_extended', 'carrier_fuel_amount', 'carrier_fuel');
					}else if(o.name == 'cust_other_amount' || o.name == 'cust_other')
					{
						update_ext('cust_other_extended', 'cust_other_amount', 'cust_other');
					}else if(o.name == 'carrier_other_amount' || o.name == 'carrier_other')
					{
						update_ext('carrier_other_extended', 'carrier_other_amount', 'carrier_other');
					}
				}
				
				function update_ext(ext_name, amt_name, rate_name)
				{
					var ext = document.getElementById(ext_name);
					var rate = document.getElementsByName(rate_name)[0];
					var amount = document.getElementsByName(amt_name)[0];
					ext.innerHTML = '$' + amount.value * rate.value;
					
				}
				function refresh_close()
				{
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
	
	function get_problem_edit_module()
	{
		$r = $this->current_row();
		$c ='';
		//$c .= $this->check_size();
		$c .= "<table width='100%' border=0>";
		$c .= '<tr><td>Problem</td><td>'.$this->fetch_edit('problem', $r['problem']).'</td><tr>';
		$c .= '<tr><td>Solution</td><td>'.$this->fetch_edit('solution', $r['solution']).'</td></tr>';
		$c .= "</table>";
		$c .= "<form>
					<input type='button' onclick='refresh_close()' value='Close'>
					</form>";
		$c .= $this->script("
				function refresh_close()
				{
					db_save(document.getElementsByName('problem')[0]);
					window.opener.update_problem_module();
					window.close();
				}");
		
		return $c;
	}
	function get_warehouse_search_result_module()
	{
		require_once("includes/portal.php");
		$c ='';
		$c .= $this->get_warehouse_search_edit_module();
		$c .= "Warehouse Search Results<br>";
		$c .= "Select Type: ".$this->get_warehouse_type_select();
		$c .= $this->script("
					
					function add_load_warehouse(warehouse_id)
					{
						if(window.opener)
						{
							var type = document.getElementById('warehouse_type');
							var obj=new Object();
							obj.id = 'table=load_warehouse&creation_date=NOW()&scheduled_with=".get_user_id()."&type='+type.value+'&warehouse_id='+warehouse_id+'&load_id=';
							obj.value = $this->load_id;
							//var param_str = 'table=load_warehouse&creation_date=NOW()&scheduled_with=".get_user_id()."&type='+type.value+'&warehouse_id='+warehouse_id+'&load_id=';
							db_save(obj);
							//db_save(param_str,$this->load_id);
							refresh_close();
						}else{
							alert('You already closed the parent window.');
						}
					}
					function refresh_close()
					{
						window.opener.update_warehouse_portal();
						//window.close();
					}");
		$sql = "SELECT CONCAT('D',warehouse_id) warehouse_id, name, address, city, state, CONCAT('<input type=\"button\" value=\"Add\" onclick=\"add_load_warehouse(',warehouse_id,')\">') `add` FROM warehouse ";
		$clause = 'WHERE';
		$where='';
		
		if(isset($_REQUEST['warehouse_id']) && intval(trim($_REQUEST['warehouse_id'], 'd D')) > 0)
		{
			$where .= " $clause warehouse_id = ".intval(trim($_REQUEST['warehouse_id'], 'd D'));
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != '')
			{
				$where .= " $clause name like '$_REQUEST[name]'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['address']) && $_REQUEST['address'] !='')
			{
				$where .= " $clause address like '$_REQUEST[address]'";
			}
			
			if(isset($_REQUEST['city']) && $_REQUEST['city'] !='')
			{
				$where .= " $clause city like '$_REQUEST[city]'";
			}
			
			if(isset($_REQUEST['state']) && $_REQUEST['state'] !='')
			{
				$where .= " $clause state like '$_REQUEST[state]'";
			}
		}
		$sql .= $where;
		//echo $sql;
		$p = new portal($sql);
		$c .= $p->render();
		
		return $c;
	}
	function get_warehouse_search_edit_module()
	{
		
		$wt = new table('warehouse');
		
		$si = new submit_input($this->warehouse_search_result, 'action');
		$wt->set_submit_input($si);
		
		
		$hi1 = new hidden_input(SMALL_VIEW);
		$wt->add_input($hi1);
		
		$hi2 = new hidden_input('page', $this->page);
		$wt->add_input($hi2);
		
		$hi3 = new hidden_input('load_id', $this->load_id);
		$wt->add_input($hi3);
		
		$f =& $wt->get_form();
		$f->set_get();
	
		$wt->omit_all_columns();
		
		$wt->insert_column('warehouse_id');
		$col =& $wt->get_column('warehouse_id');
		$o =& $col->get_edit_html();
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
	function get_carrier_search_result_module()
	{
		require_once("includes/portal.php");
		$c ='';
		$c .= $this->get_carrier_search_edit_module();
		$c .= "Carrier Search Results<br>";
		
		$c .= $this->script("
					function check_carrier(insurance_expires)
					{
						
						ins_exp_str = insurance_expires.split('-');
						ins_exp = new Date();
						ins_exp.setYear(ins_exp_str[0]);
						ins_exp.setMonth(ins_exp_str[1]);
						ins_exp.setDate(ins_exp_str[2]);
						today = new Date();
						//alert('insurance_expires:'+ins_exp.toString()+' today:'+today.toString());
						if(ins_exp > today)
						{
							return true;
						}else
						{
							return false;
						}
					}
					function add_load_carrier(carrier_id, insurance_expires)
					{
						if(check_carrier(insurance_expires))
						{
							var param_str = 'table=load_carrier&carrier_id='+carrier_id+'&load_id=';
							var obj=new Object();
							obj.id  = param_str;
							obj.value = $this->load_id;
							db_save(obj);
							//db_save(param_str,$this->load_id);
							refresh_close();
						}else{
							alert(\"Carrier's insurance expired on \"+insurance_expires+\".\")
						}
					}
					function refresh_close()
					{
						window.opener.update_carrier_portal();
						//window.close();
					}");
		$sql = "SELECT	CONCAT('S', carrier_id) carrier_id,
						name,
						phys_city,
						phys_state,";
		$sql .= "		IF(!do_not_load, CONCAT('<input type=\"button\" value=\"Add\" onclick=\"add_load_carrier(',carrier_id,',\'',insurance_expires,'\')\">'), null) `add` ";
		//$sql .= "		CONCAT('<input type=\"button\" value=\"Add\" onclick=\"add_load_carrier(',carrier_id,',\'',insurance_expires,'\')\">') `add` ";
		$sql .= "FROM carrier ";
		$clause = 'WHERE';
		$where='';
		if(isset($_REQUEST['carrier_id']) && intval(trim($_REQUEST['carrier_id'], 's S')) > 0)
		{
			$where .= " $clause carrier_id = ".intval(trim($_REQUEST['carrier_id'], 's S'));
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != '')
			{
				$where .= " $clause name like '$_REQUEST[name]'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['phys_address']) && $_REQUEST['phys_address'] !='')
			{
				$where .= " $clause phys_address like '$_REQUEST[phys_address]'";
			}
			if(isset($_REQUEST['phys_city']) && $_REQUEST['phys_city'] !='')
			{
				$where .= " $clause phys_city like '$_REQUEST[phys_city]'";
			}
			if(isset($_REQUEST['phys_state']) && $_REQUEST['phys_state'] !='')
			{
				$where .= " $clause phys_state like '$_REQUEST[phys_state]'";
			}
		}
		$sql .= $where;
		//echo $sql;
		$p = new portal($sql);
		$c .= $p->render();
		
		return $c;
	}
	function get_carrier_search_edit_module()
	{
		
		$wt = new table('carrier');
		
		$si = new submit_input($this->carrier_search_result, 'action');
		$wt->set_submit_input($si);
		
		$hi1 = new hidden_input(SMALL_VIEW);
		$wt->add_input($hi1);
		
		$hi2 = new hidden_input('page', $this->page);
		$wt->add_input($hi2);
		
		$hi3 = new hidden_input('load_id', $this->load_id);
		$wt->add_input($hi3);
		
		$f =& $wt->get_form();
		$f->set_get();
	
		$wt->omit_all_columns();
		$wt->unhide_column('carrier_id');
		$wt->insert_column('carrier_id');
		$wt->insert_column('name');
		$wt->insert_column('phys_city');
		$wt->insert_column('phys_state');
		return $wt->_render_edit();
	}
	
	//===== Customer Search functions =====
	function get_customer_search_result_module()
	{
		require_once("includes/portal.php");
		$c ='';
		$c .= $this->get_customer_search_edit_module();
		$c .= "Customer Search Results<br>";
		
		$c .= $this->script("
					
					function set_customer(customer_id)
					{
						var param_str = 'table=load&action=$this->update&customer_id='+customer_id+'&load_id=';
						var obj=new Object();
						obj.id = param_str;
						obj.value = $this->load_id;
						db_save(obj);
						//db_save(param_str,$this->load_id);
						refresh_close();
					}
					function refresh_close()
					{
						window.opener.update_cust_module();
						window.close();
					}");
		$sql = "SELECT	CONCAT('T', customer_id) customer_id,
						name,
						address,
						city,
						state,
						CONCAT('<input type=\"button\" value=\"Add\" onclick=\"set_customer(',customer_id,')\">') `add` FROM customer ";
		$clause = 'WHERE';
		$where='';
		if(isset($_REQUEST['customer_id']) && intval(trim($_REQUEST['customer_id'], 't T')) > 0)
		{
			$where .= " $clause customer_id = ".intval(trim($_REQUEST['customer_id'], 't T'));
		}else{
			if(isset($_REQUEST['name']) && $_REQUEST['name'] != '')
			{
				$where .= " $clause name like '$_REQUEST[name]'";
				$clause = 'AND';
			}
			if(isset($_REQUEST['address']) && $_REQUEST['address'] !='')
			{
				$where .= " $clause address like '$_REQUEST[address]'";
			}
			if(isset($_REQUEST['city']) && $_REQUEST['city'] !='')
			{
				$where .= " $clause city like '$_REQUEST[city]'";
			}
			if(isset($_REQUEST['state']) && $_REQUEST['state'] !='')
			{
				$where .= " $clause state like '$_REQUEST[state]'";
			}
		}
		$sql .= $where;
		//echo $sql;
		$p = new portal($sql);
		$c .= $p->render();
		
		return $c;
	}
	function get_customer_search_edit_module()
	{
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
		
		$f =& $wt->get_form();
		$f->set_get();
	
		$wt->omit_all_columns();
		$wt->insert_column('customer_id');
		$wt->insert_column('name');
		$wt->insert_column('city');
		$wt->insert_column('state');
		
		$c .= $wt->_render_edit();
		
		return $c;
	}
	
	
	function get_problem_module()
	{
		$r = $this->current_row();
		$c = "<table width='100%' border=0 class='content'>";
		$c .= "<tr><td>Problem:</td><td class='faux_edit'>$r[problem]</td><tr>";
		$c .= "<tr><td>Solution:</td><td class='faux_edit'>$r[solution]</td></tr>";
		$c .= "</table>";
	
		return $c;
	}
	
	function get_money_module()
	{
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
		
		$c = "<table width='100%' border=0 class='content'>";
		$c .= "<tr><td class='right bold'>Customer Total:</td><td class='faux_edit'>$<span id='customer_total' class='left bold'>$customer_total</span></td>";
		$c .= "<td class='right bold'>Carrier Total:</td><td class='faux_edit'>$<span id='carrier_total' class='left bold'>$carrier_total</span></td>";
		$c .= "<td class='right bold'>GP:</td><td class='faux_edit'>$<span id='gp' class='bold'></span></td>";
		$c .= "<td class='right bold'>GPP:</td><td class='faux_edit'><span id='gpp' class='bold'>%</span></td></tr>";
		$c .= "</table>";
		return $c;
	}
	
		
	function get_cust_module()
	{
		$r = $this->current_row();
		$c = "<table style='width:100%;border:1px solid black;' class='content'><tr>";
		if(isset($r['customer_id']))
		{
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
	
	function get_rating_edit_module()
	{
		$r = $this->current_row();
		$c = "<table width='100%' border=0>";
		$col =& $this->get_column('rating');
		$rating_list = Array('Standard' => 'Standard', 'Expedited' => 'Expedited');
		$col->set_value_list($rating_list);
		$c .= '<tr><td>Rating Code</td><td>'.$this->fetch_edit('rating', $r['rating']).'</td><tr>';
		$c .= '<tr><td>'.$this->fetch_edit('cancelled', $r['cancelled']).'</td><td>Cancel</td></tr>';
		
		$c .= "</table>";
		$c .= "<form>
					<input type='button' onclick='refresh_close()' value='Close'>
					</form>";
		$c .= $this->script("
				function refresh_close()
				{
					window.opener.update_rating_module();
					window.close();
				}");
		
		return $c;
	}
	
	function get_rating_module()
	{
		$r = $this->current_row();
		if($r['cancelled'])
		{
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
	
	function get_cust_edit_module()
	{
		$r = $this->current_row();
		$c = "<table><tr>";
		$c .= "<td>".$this->fetch_edit('customer_id', $r['customer_id'])."</td>";
		$c .= "</tr></table>";
		$c .= "<form>
					<input type='button' onclick='refresh_close()' value='Close'>
					</form>";
		$c .= $this->script("
				function refresh_close()
				{
					window.opener.update_cust_module();
					window.close();
				}");
		return $c;
	}
	
	function get_load_edit_module()
	{
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
				function refresh_close()
				{
					window.opener.update_load_module();
					window.close();
				}");
		return $c;
	}
	function get_order_edit_module()
	{
		$r = $this->current_row();
		$c = "<table width='100%' border=0>";
		$c .= '<tr><td>Order By</td><td>'.$this->fetch_edit('order_by', $r['order_by']).'</td></tr>';
		$c .= "</table>";
		$c .= "<form>
					<input type='button' onclick='refresh_close()' value='Close'>
					</form>";
		$c .= $this->script("
				function refresh_close()
				{
					window.opener.update_order_module();
					window.close();
				}");
		return $c;
	}
	
	function get_order_module()
	{
		$r = $this->current_row();
		$user = $this->get_user($r['order_by']);
		$c = "<table width='100%' border=0>";
		$c .= "<tr><td>Order By</td><td class='faux_edit'>$user[username]</td></tr>";
		$c .= "</table>";
	
		return $c;
	}
	
	function get_load_edit()
	{
		
		$r = $this->current_row();
		if($r['cancelled'])
		{
			$c = $this->style("
				.load_content{background-color:$this->cancel_color}");
		}else
		{
			$c = $this->style("
				.load_content, .content{background-color:#EEEEEE}");
		}
		$auth_edit=false;
		if(!$this->been_delivered() && (logged_in_as('admin') || get_user_id() == $r['order_by']))
		{
			$auth_edit=true;
		}
		$c .= $this->popup_script();
		$c .= "<center><h2>#$this->load_id</h2>";
		
		//==== Repeat Load Button ====
		$c .= "<form method='post' action='http://".HTTP_ROOT."/?page=$this->page&action=$this->repeat&load_id=$this->load_id'><input type='submit' value='Repeat'></form>";
		
						
		$c .= "<table width='100%'><tr>";
		$c .= "<td valign='top' width='33%' class='bottom_pad'>";
		
		//==== Order ====
		$c .= "
		<fieldset style='height:$this->row_height'>
			<legend>Order</legend>
			<div id='order_module'>
				<table width='100%' border=0>
					<tr>
						<td>Order By:</td>
						<td>". $this->get_order_by_name()."</td>
					</tr>
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
		
		//====Rating====
		$col =& $this->get_column('rating');
		$rating_list = Array('Standard' => 'Standard', 'Expedited' => 'Expedited');
		$col->set_value_list($rating_list);
		$c .= "
		<fieldset style='height:$this->row_height'>
			<legend>Rating</legend>";
		$c .= "
			<div id='rating_module'>";
		$c .= "
				<table width='100%' border=0>
					<tr>
						<td class='right'>Rating Code:</td>
						<td class='left'>".$this->fetch_edit('rating', $r['rating'])."</td>
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
				function set_content_color()
				{
					if(!cont)
					{
						cont = document.getElementById('content');
					}
					var cancelled = document.getElementsByName('cancelled');
					cancelled = cancelled[0];
					
					var rating = document.getElementsByName('rating');
					//alert(rating.count)
					rating = rating[0];
					
					if(cancelled.checked)
					{
						cont.style.backgroundColor = '$this->cancel_color';
						//cont.setAttribute('background-color', '$this->content_color');
					}else if(rating.value == 'Expedited')
					{
						cont.style.backgroundColor = '$this->expedited_color';
						//cont.setAttribute('background-color', '$this->content_color');
					}else
					{
						cont.style.backgroundColor = '$this->content_color';
						//cont.setAttribute('background-color', '$this->content_color');
					}
					//alert(cont.style.backgroundColor);
					return true;
				}");
		
		//==================
			$c .= "</td>
					<td valign='top' width='33%' class='bottom_pad'>";
		//====Problem====
		$c .= "
		<fieldset style='height:$this->row_height'>
				<legend>";
		if($auth_edit)
		{
			$c .= "<a href='#' onclick=\"javascript:popUp('http://".HTTP_ROOT."/?page=$this->page&action=$this->edit_str&module=$this->problem_str&load_id=$this->load_id&".SMALL_VIEW."')\">Problem</a>";
		}else{
			$c .= 'Problem';
		}
		$c .= "</legend>
				<div id='problem_module'></div>
		</fieldset>";
		$c .= $this->script("
					function update_problem_module()
					{
						//
						get_module('problem', 'action=view&load_id=$this->load_id');
						//alert('updated');
					}
					update_problem_module();");
		
		//==================
		$c .= "	</td>
				<td valign='top'>";
		$c .= "	</td>
				</tr>
				<tr>
				<td colspan=3 class='bottom_pad'>";
		//==== Customer ====
		
		$c .= "
		<fieldset>
				<legend>";
		//if($auth_edit)
		//{
			$c .= "<a href='#' onclick=\"javascript:popUp('http://".HTTP_ROOT."/?page=$this->page&action=$this->customer_search_edit&load_id=$this->load_id&".SMALL_VIEW."', 700,550)\">Customer</a>";
		//}else{
		//	$c .= 'Customer';
		//}
		//$c .= $r['customer_id'];
		$c .="</legend>
			<div id='customer_module'></div>
		</fieldset>";
		$c .= $this->script("
					function update_cust_module()
					{
						get_module('customer', 'action=view&load_id=$this->load_id');
					}
					update_cust_module();");
		
		//============
		$c .= "</td></tr><tr><td colspan=3 class='bottom_pad'>";
		//====Load====
		$c .= "
		<fieldset>
				<legend>Load</legend>
					<div id='load_module'>";
		$c .= "<table width='100%'><tr>";
		$c .= "<td class='label_class'>Trailer Type:</td><td>".$this->fetch_edit('trailer_type', $r['trailer_type'])."</td>";
		$c .= "<td class='label_class'>Length (inches):</td><td>".$this->fetch_edit('length', $r['length'])."</td>";
		$c .= "<td class='label_class'>Size:</td><td>".$this->fetch_edit('size', $r['size'])."</td></tr>";
		$c .= "<tr><td class='label_class'>Pallets:</td><td>".$this->fetch_edit('pallets', $r['pallets'])."</td>";
		$c .= "<td class='label_class'>Weight (lbs.):</td><td>".$this->fetch_edit('weight', $r['weight'])."</td>";
		$c .= "<td class='label_class'>Class:</td><td>".$this->fetch_edit('class', $r['class'])."</td></tr>";
		$c .= "<tr><td class='label_class'>Commdity:</td><td>".$this->fetch_edit('commodity', $r['commodity'])."</td>";
		$c .= "</tr></table>";
		$c .= "		</div>";
		$c .= "</fieldset>";
		//================
		$c .= "</td></tr><tr><td colspan=3 class='bottom_pad'>";
		//=== Warehouse =======
		$c .= "
		<fieldset>
				<legend>";
		if($auth_edit)
		{
			$c .= "<a href='#' onclick=\"javascript:popUp('http://".HTTP_ROOT."/?page=$this->page&load_id=$this->load_id&action=$this->warehouse_search_edit&customer_id=$r[customer_id]&".SMALL_VIEW."', 700, 550)\">Warehouse</a>";
		}else{
			$c .= 'Warehouse';
		}
		$c .= "</legend>";
		$c .= "<div id='load_warehouse_portal'></div>";
		$c .= "</fieldset>";
		$c .= $this->script("		
			function add_load_warehouse(load_id)
			{
				var warehouse_selection = document.getElementById('warehouse_selection');
				var type = document.getElementById('warehouse_type');
				var param_str = 'table=load_warehouse&type='+type.value+'&warehouse_id='+warehouse_selection.value+'&load_id=';
				var obj=new Object();
				obj.id = param_str;
				obj.value = load_id;
				db_save(obj);
				//db_save(param_str,load_id);
				update_warehouse_portal();
			}
			
			function update_warehouse_portal()
			{
				get_portal('load_warehouse', 'load_id=$this->load_id');
				init_cals();
			}
			
			function delete_warehouse(warehouse_id)
			{
				if(confirm('Are you sure you want to delete warehouse '+warehouse_id+' from this load?'))
				{
					var obj=new Object();
					obj.id = 'action=$this->delete&table=load_warehouse&load_id=$this->load_id&warehouse_id=';
					obj.value = warehouse_id;
					db_save(obj);
					//db_save('action=$this->delete&table=load_warehouse&load_id=$this->load_id&warehouse_id=', warehouse_id);
					update_warehouse_portal();
					//alert('success');
				}
			}
			var cal_act_dates = Array();
			function init_cals()
			{
				include('./CalendarPopup.js');
				
				var cals = document.getElementsByName('cal_act_date');
				for(i=0;i<cals.length;i++)
				{
					cal_act_dates[i] = new CalendarPopup(cals[i].id);
					cal_act_dates[i].id = cals[i].id+'_cal';
					//alert(cal_act_dates[i]);
				}
			}
			update_warehouse_portal();");
		
		
		//==================
		$c .= "</td></tr><tr><td colspan=3 class='bottom_pad'>";
		//====Money====
		$c .= "
		<fieldset>
				<legend>";
		if(!$this->been_delivered() || logged_in_as('admin'))
		{
			$c .= "<a href='#' onclick=\"javascript:popUp('http://".HTTP_ROOT."/?page=$this->page&action=$this->edit_str&module=$this->money_str&load_id=$this->load_id&".SMALL_VIEW."',0,1064,240)\">Money</a>";
		}else{
			$c .= 'Money';
		}
		$c .= "</legend><div id='money_module'></div>";
		$c .= "</fieldset>";
		$c .= $this->script("
					function update_money_module()
					{
						get_module('$this->money_str', 'action=view&load_id=$this->load_id');
						update_gp();
					}
					update_money_module();");
		//==================
		$c .= "</td></tr><tr><td colspan=3>";
		//====Carriers====
		$c .= "
		<fieldset>
				<legend>";
		//if($auth_edit)
		//{
			$c .= "<a href='#' onclick=\"javascript:popUp('http://".HTTP_ROOT."/?page=$this->page&load_id=$this->load_id&action=$this->carrier_search_edit&".SMALL_VIEW."','carrier_search_'+$this->load_id, 700, 550)\">Carrier</a>";
		//}else{
		//	$c .= 'Carrier';
		//}
		$c .= "</legend><div id='load_carrier_portal'></div>";
		$c .= "</fieldset>";
		$c .= $this->script("
					function cancel()
					{
						alert(this.value);
						var c = document.getElementById('content');
						c.style.color = '$this->cancel_color';
					}
					function add_load_carrier(load_id)
					{
						var carrier_selection = document.getElementById('carrier_selection');
						var param_str = 'table=load_carrier&carrier_id='+carrier_selection.value+'&load_id=';
						var obj=new Object();
						obj.id = param_str;
						obj.value = load_id;
						db_save(obj);
						//db_save(param_str,load_id);
						update_carrier_portal();
					}
					function update_carrier_portal()
					{
						get_portal('load_carrier', 'load_id=$this->load_id');
					}
					
					function delete_carrier(carrier_id)
					{
						if(confirm('Are you sure you want to delete carrier '+carrier_id+' from this load?'))
						{
							var obj=new Object();
							obj.id = 'action=$this->delete&table=load_carrier&load_id=$this->load_id&carrier_id=';
							obj.value = carrier_id;
							db_save(obj);
							//db_save('action=$this->delete&table=load_carrier&load_id=$this->load_id&carrier_id=', carrier_id);
							update_carrier_portal();
							//alert('success');
						}
					}
					update_carrier_portal();");
		
		//================
		$c .= "</td></tr><tr><td colspan=3>";
		$col = $this->get_column('ltl_carrier');
		$col->set_value_list($this->ltl_carriers);
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
		function open_rate_conf(url, id)
		{
			eval(\"window.open(url, '\" + id + \"', 'toolbar=0, scrollbars=1, location=0, statusbar=0, menubar=0, resizable=0, width=700, height=600, left = 0, top = 0');\");
		}");
		$c .= "</td></tr></table>";
		
		return $c;
	}
	
	function create_new()
	{
		set_post('order_date', 'NOW()');
		set_post('order_by', get_user_id());
		$this->add();
		if(db_error())
		{
			echo db_error();
			$this->add_error();
			$this->add_error($sql);
		}
		$this->load_id = $this->last_id;
	}
	function get_new()
	{
		
		$c = $this->script("
				function submit_close()
				{
					var f = document.getElementById('new_form');
					f.submit();
				}
				function cancel_close()
				{
					window.close();
				}");
		$c .= '<center><table>';
		$c .= "<form id='new_form' onsubmit='submit_close();' method='post'>";
		$c .= "<input type='hidden' name='page' value='$this->page'>";
		$c .= "<input type='hidden' name='action' value='$this->add_str'>";
		
		$c .= '<tr><td>Rating</td><td>'.$this->fetch_new('rating').'</td></tr>';
		$c .= '<tr><td>Ordered</td><td>'.$this->fetch_new('ordered').'</td></tr>';
		$c .= '<tr><td>Customer</td><td>'.$this->fetch_new('customer_id', safe_get($_REQUEST['customer_id'])).'</td></tr>';
		//$c .= '<tr><td>Origin</td><td>'.$this->fetch_new('origin').'</td></tr>';
		//$c .= '<tr><td>Dest</td><td>'.$this->fetch_new('dest').'</td></tr>';
		$c .= '<tr><td>Customer Total</td><td>'.$this->fetch_new('customer_total').'</td></tr>';
		$c .= '<tr><td>Carrier Total</td><td>'.$this->fetch_new('carrier_total').'</td></tr>';
		$c .= '<tr><td>Trailer Type</td><td>'.$this->fetch_new('trailer_type').'</td></tr>';
		$c .= '<tr><td>Pallets</td><td>'.$this->fetch_new('pallets').'</td></tr>';
		$c .= '<tr><td>Length</td><td>'.$this->fetch_new('length').'</td></tr>';
		$c .= '<tr><td>Size</td><td>'.$this->fetch_new('size').'</td></tr>';
		$c .= '<tr><td>Weigth</td><td>'.$this->fetch_new('weight').'</td></tr>';
		$c .= '<tr><td>Class</td><td>'.$this->fetch_new('class').'</td></tr>';
		$c .= '<tr><td>Carrier</td><td>'.$this->fetch_new('carrier_id').'</td></tr>';
		$c .= '<tr><td>Order By</td><td>'.$this->fetch_new('order_by', safe_get(get_user_id($_REQUEST[COOKIE_USERNAME]))).'</td></tr>';
		if(isset($_REQUEST[SMALL_VIEW]))
		{
			$c .= '<tr><td><input type="button" onclick="submit_close()" value="$this->add_str"></td></tr>';
			$c .= '<tr><td><input type="button" onclick="cancel_close()" value="$this->cancel_str"></td></tr>';
		}else{
			$c .= "<tr><td><input type='submit' name='action' value='$this->add_str'></td></tr>";
			$c .= "<tr><td><input type='submit' name='action' value='$this->cancel_str'></td></tr>";
		}
		$c .= '</form></table></center>';
		
		return $c;
	}

	function get_customer($customer_id)
	{
		$sql ="SELECT *, CONCAT('<a href=\"http://".HTTP_ROOT."/?page=customer&action=$this->edit_str&customer_id=',customer_id,'\">', name, '</a>') name FROM customer WHERE customer_id = $customer_id";
		$r = db_query($sql);
		return db_fetch_assoc($r);
	}
	
	function get_user($user_id)
	{
		
		$sql ="SELECT * FROM users WHERE user_id = $user_id";
		$r = db_query($sql);
		return db_fetch_assoc($r);
	}
	
	function fetch_new($name, $value=null)
	{
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		
		$o = $c->get_edit_html($value);
		
		return $o->render();
	}
	
	function fetch_edit_old($name, $value=null)
	{
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		
		$o =& $c->get_edit_html($value);
		$o->set_id("action=$this->update&table=$this->name&load_id=$this->load_id&".$name."=");
		$o->add_attribute('onchange', 'db_save(this);return column_updated(this);');
		
		//$o->add_attribute('onchange', 'db_save(this.id, this.value);column_updated(this);');
		$script = $this->script("
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);");
		
		return $o->render();
	}
	function fetch_edit($name, $value=null, $protected=false)
	{
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		if($protected)
		{
			return "<div class='faux_edit'>".$c->get_view_html($value)."</div>";
		}else{
			$o =& $c->get_edit_html($value);
			$o->set_id("action=$this->update&table=$this->name&load_id=$this->load_id&".$name."=");
			$o->add_attribute('onchange', 'db_save(this);return column_updated(this);');
		
			$script = $this->script("
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);");
		
		return $o->render();
		}
	}
	function fetch_edit_lc($t, $c_id, $name, $value)
	{
		$c =& $t->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		
		$o = $c->get_edit_html($value);
		$o->set_id("action=$this->update&table=$t->name&load_id=$this->load_id&carrier_id=$c_id&$name=");
		$o->add_attribute('onchange', 'db_save(this);column_updated(this);');
		$script = $this->script("
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);");
		
		return $o->render();
	}
	
	function get_daily_profit($date)
	{
			$sql = "SELECT 
				round(sum(
					((cust_line_haul * cust_line_haul_amount) + 
					(cust_detention * cust_detention_amount) + 
					(cust_tonu * cust_tonu_amount) + 
					(cust_unload_load * cust_unload_load_amount) +
					(cust_fuel * cust_fuel_amount) + 
					(cust_other * cust_other_amount))
					-
					((carrier_line_haul * carrier_line_haul_amount) + 
					(carrier_detention * carrier_detention_amount) + 
					(carrier_tonu * carrier_tonu_amount) + 
					(carrier_unload_load * carrier_unload_load_amount) +
					(carrier_fuel * carrier_fuel_amount) + 
					(carrier_other * carrier_other_amount))),2) profit
					FROM `load` l
					WHERE activity_date = '$date'
					AND load_id in (SELECT load_id FROM load_carrier)";
		$result = db_query($sql);
		if(db_error())
		{
			echo db_error();
			echo $sql;
		}
		//echo $sql;
		$r = db_fetch_assoc($result);
		return $r['profit'];
	}
	function load_board()
	{
		if (isset($_REQUEST['day']))
		{
			$cur_day = $_REQUEST['day'];
		}else
		{
			$cur_day = date("d");
		}
		
		if (isset($_REQUEST['month']))
		{
			$cur_month = $_REQUEST['month'];
		}else
		{
			$cur_month = date("m");
		}
		if (isset($_REQUEST['year']))
		{
			$cur_year = $_REQUEST['year'];
		}else
		{
			$cur_year = date("Y");
		}
		
		$cur_date_str = $cur_day . " " . $this->get_month_name($cur_month) . " " . $cur_year;
		$prev_date_str = $cur_date_str . " -1 day";
		$next_date_str = $cur_date_str . " +1 day";
		$db_date = $cur_year.'-'.$cur_month.'-'.$cur_day;
		$next_date = strtotime($next_date_str);
		$prev_date = strtotime($prev_date_str);
		$prev_day = date("d", $prev_date);
		$prev_month = date("m", $prev_date);
		$prev_year = date("Y", $prev_date);
		
		$next_day = date("d", $next_date);
		$next_month = date("m", $next_date);
		$next_year = date("Y", $next_date);
		require_once('includes/calendar.php');
		$cal = new calendar();
		$cal->add_attrib('page', $this->page);
		$c = "
			<center>
				<h2>Load Board</h2>";
		if(logged_in_as('admin'))
		{
			$c .= "Daily profit: $".$this->get_daily_profit($db_date);
		}
		$c .="	
			<table width='100%'>
				<tr>
					<td>
						<a href='http://".HTTP_ROOT."/?page=$this->page&day=$prev_day&month=$prev_month&year=$prev_year'>Previous Day</a>
					</td>
					<td>
						<center>
							".$cal->get_navigator()."
						</center>
					</td>
					<td align='right'>
						<a href='http://".HTTP_ROOT."/?page=$this->page&day=$next_day&month=$next_month&year=$next_year'>Next Day</a>
					</td>
				</tr>
				<tr>
					<td colspan=3>
			<table style='width:100%'>
				<tr>
					<td style='border:1px solid black;width:25%'>
						Ordered
					</td>
					<td style='border:1px solid black;width:25%'>
						Booked
					</td>";
		//$c .= "		<td style='border:1px solid black'>Checked In</td>";
		$c .= "		<td style='border:1px solid black;width:25%'>
						Loaded
					</td>
					<td style='border:1px solid black;width:25%'>
						Delivered
					</td>
				</tr>
				<tr>
					<td style='border:1px solid black' valign='top'>"
		.$this->get_loads($db_date, $this->ordered).
					"</td>
					<td style='border:1px solid black' valign='top'>"
		.$this->get_loads($db_date, $this->booked).
					"</td>";
		//$c .= "		<td style='border:1px solid black' valign='top'>".$this->get_loads($db_date, 'checked_in')."</td>";
		$c .= "		<td style='border:1px solid black' valign='top'>"
		.$this->get_loads($db_date, $this->loaded).
				"	</td>
					<td style='border:1px solid black' valign='top'>"
		.$this->get_loads($db_date, $this->delivered).
				"	</td>
				</tr>
			</table>
			</td>
			</tr>
			</table>
			</center>
				<style>
			
			/*.tableContainer td
			{
				border-bottom:1px solid black;
			}*/
		</style>";
	
		return $c;
	}
		function get_loads($date, $type)
	{
		require_once'includes/portal.php';
		$q = new portal();
		$sql = "	SELECT 	load_id, CONCAT('<span class=\"red\">', RIGHT(l.load_id, 4), '</span>') 'id',
								IFNULL(
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
							FROM `load` l
							WHERE l.activity_date = '$date'";
							
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
		if($type == $this->delivered)
		{
			//A carrier has been booked...
			$sql .= $has_been_booked;
			// has been loaded
			$sql .=	$has_been_loaded;
								
			// and has been delivered
			$sql .= $has_been_delivered;
		}
		
		//==== Loaded ====
		if($type == $this->loaded)
		{
			//A carrier has been booked...
			$sql .= $has_been_booked;
			// has been loaded
			$sql .=	$has_been_loaded;
			
			//but NOT delivered 
			$sql .= $not_delivered;
		}
		
		if($type == $this->booked)
		{
			//A carrier has been booked...
			$sql .= $has_been_booked;
			//echo $sql;
			
			// AND NOT loaded
			$sql .=	$not_loaded;
			//OR NOT delivered 
			$sql .=  $not_delivered;
		}
		
		if($type == $this->ordered)
		{
			//A carrier has NOT been booked...
			$sql .= "	AND (SELECT count(*) FROM load_carrier lc WHERE lc.load_id = l.load_id) = 0";
			
			// AND NOT loaded
			$sql .=	$not_loaded;
			//OR NOT delivered 
			$sql .= $not_delivered;
			//echo $sql;
		}
		$q->set_sql($sql);
		$q->set_table('load');
		$q->hide_column('load_id');
		$q->set_primary_key('load_id');
		$q->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		$c .=$q->render();
		return $c;
	}
	function get_loads_old($date, $type)
	{
		require_once'includes/portal.php';
		$q = new portal();
		$sql = "	SELECT 	l.load_id,
								IFNULL(
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
							
		$has_been_loaded = "	AND (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'PICK'
								AND STR_TO_DATE(CONCAT(activity_date, ' ', activity_time), '$this->db_date_format $this->db_time_format') < NOW()) > 0";
		
		$has_been_delivered ="	AND (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'DEST'
								AND STR_TO_DATE(CONCAT(activity_date, ' ', activity_time), '$this->db_date_format $this->db_time_format') < NOW()) > 0";
		$not_delivered = "	AND ( (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'DEST'
								AND STR_TO_DATE(CONCAT(activity_date, ' ', activity_time), '$this->db_date_format $this->db_time_format') > NOW()) > 0
						OR (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'DEST') = 0)";
		$not_loaded ="	AND ((	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'PICK'
								AND STR_TO_DATE(CONCAT(activity_date, ' ', activity_time), '$this->db_date_format $this->db_time_format') > NOW()) > 0
						OR (	SELECT count(*)
								FROM load_warehouse lw
								WHERE lw.load_id = l.load_id
								AND lw.type = 'PICK') = 0)";
		$has_been_booked = "	AND (SELECT count(*) FROM load_carrier lc WHERE lc.load_id = l.load_id) > 0";
		if($type == $this->delivered)
		{
			//A carrier has been booked...
			$sql .= $has_been_booked;
			// has been loaded
			$sql .=	$has_been_loaded;
								
			// and has been delivered
			$sql .= $has_been_delivered;
		}
		
		if($type == $this->loaded)
		{
			//A carrier has been booked...
			$sql .= $has_been_booked;
			// has been loaded
			$sql .=	$has_been_loaded;
			
			//but NOT delivered 
			$sql .= $not_delivered;
		}
		
		if($type == $this->booked)
		{
			//A carrier has been booked...
			$sql .= $has_been_booked;
			//echo $sql;
			
			// AND NOT loaded
			$sql .=	$not_loaded;
			//OR NOT delivered 
			$sql .=  $not_delivered;
		}
		
		if($type == $this->ordered)
		{
			//A carrier has NOT been booked...
			$sql .= "	AND (SELECT count(*) FROM load_carrier lc WHERE lc.load_id = l.load_id) = 0";
			
			// AND NOT loaded
			$sql .=	$not_loaded;
			//OR NOT delivered 
			$sql .= $not_delivered;
			//echo $sql;
		}
		$q->set_sql($sql);
		$q->set_table('load');
		$q->hide_column('load_id');
		$q->set_primary_key('load_id');
		$q->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		//$c = $sql;//debug
		$c .=$q->render();
		return $c;
	}
	
	function get_month_name($dayInt)
	{
		$monthAry = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		$dayInt = ltrim($dayInt, "0");
		return $monthAry[$dayInt];
	}
	
	function get_carriers_selection()
	{
		require_once("includes/select_input.php");
		$sql = "SELECT * FROM carrier";
		$r = db_query($sql);
		$select = new select_input("carrier_id", "carrier_id", "name", $r);
		$select->set_id("carrier_selection");
	
		return $select->render();
	}
	
	function get_warehouse_selection($customer_id)
	{
		require_once("includes/select_input.php");
		$sql = "SELECT * FROM warehouse w WHERE w.customer_id = $customer_id";
		$r = mysql_query($sql);
		$select = new select_input("warehouse_id", "warehouse_id", "name", $r);
		$select->set_id("warehouse_selection");
	
		return $select->render();
	}
	
	function get_warehouse_type_select()
	{
		require_once("includes/select_input.php");
		
		$select = new select_input("type", null, null, $this->warehouse_types);
		$select->set_id("warehouse_type");

		return $select->render();
	}
	
	function gp_script()
	{
		return $this->script("
			function column_updated(t)
			{
				//alert(t.name);
				if (t.name == 'customer_total' || t.name == 'carrier_total')
				{
					update_gp();
				}else if(t.name == 'cancelled' || t.name == 'rating')
				{
					//alert('snood');
					return set_content_color();
				}
			}
			
			function update_gp()
			{
				var gp = document.getElementById('gp');
				var gpp = document.getElementById('gpp');
				
				var cust_total_el = document.getElementById('customer_total');
				var car_total_el = document.getElementById('carrier_total');
				var cust_total = cust_total_el.innerHTML.valueOf();
				var car_total = car_total_el.innerHTML.valueOf();
				
				var gp_val = (cust_total - car_total).toFixed(2)||0;
				//alert(cust_total);
				//var gp_val = (parseInt(cust_total.innerHTML) - parseInt(car_total.innerHTML)) ||0;
				gp.innerHTML = gp_val;
				
				//if(Math.round(gp_val*100) != 0 && Math.round(gp_val) > 0)
				if(Math.round(gp_val*100) != 0 && Math.round(gp_val) > 0)
				{
					gpp.innerHTML = ((gp_val*100)/ (cust_total)).toFixed(2) +'%';
				}else{
					gpp.innerHTML = 0+'%';
				}
			}
			update_gp();");
	}
	
	function get_rate_conf()
	{
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
						(SELECT CONCAT(u.first_name, ' ', u.last_name) FROM users u WHERE u.user_id=lc.booked_with) booked_name,
						(SELECT username FROM users WHERE user_id=lc.booked_with) booked_with,
						(SELECT username FROM users, customer c WHERE user_id = c.acct_owner AND c.customer_id = l.customer_id) booked_salesperson,
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
		$res = mysql_query($sql);
		//echo $sql."<br>";
		if(mysql_error())
		{
			echo $sql."<br>";
			echo mysql_error();
		}
		$r = mysql_fetch_assoc($res);
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
								<img src='http://".IMG_ROOT."/dts.gif'>
							</td>
							<td style='vertical-align:middle'>
							<center><div style='font-size:16pt;padding:3pt;width:4em;' class='bold heavy_frame'>$_REQUEST[load_id]</div>
							</td>
							<td width=30% style='vertical-align:middle'>";
		if($r['ltl_number'])
		{
		$header .= "				
							
								<center><span style='padding:6pt;' class='bold heavy_frame'>$r[ltl_number]
								</span>
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
		while($pick = db_fetch_assoc($picks))
		{
			
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
			//$origin .= "<td></td><td></td>";
			$origin .= "</tr><tr>";
			$origin .= "<td>Phone:</td><td class='bold'>$pick[origin_phone]</td>";
			$origin .= "<td width='$gutter'></td>";
			$origin .= "<td>Notes:</td><td class= bold'>$pick[origin_notes]</td>";
			//$origin .= "</tr></table>";
			$origin .= "</tr><tr>";
			$origin .= "<td colspan=5><hr></td>";
			//$origin .= "<hr>";
			$origin .= "</tr><tr>";
		}
		//$dest = "<table width='80%'><tr>";
		$dest .= "<td>CONS:</td><td class='bold'>$r[dest_name]</td>";
		$dest .= "<td width='$gutter'></td>";
		$dest .= "<td>Delivery Date:</td><td class='bold'>$r[delivery_date]</td>";
		$dest .= "</tr><tr>";
		$dest .= "<td rowspan=2>Address:</td><td rowspan=2 class='bold'>$r[dest_address]<br>$r[dest_city] $r[dest_state] $r[dest_zip]</td>";
		$dest .= "<td width='$gutter'></td>";
		$dest .= "<td>Delivery&nbsp;Time:</td><td class='bold'>".nbsp($r['delivery_time'])."</td>";
		$dest .= "</tr><tr>";
		$dest .= "<td width='$gutter'></td>";
		$dest .= "<td>Dest #:</td><td class='bold'>$r[dest_num]</td>";
		$dest .= "</tr><tr>";
		$dest .= "<td>Phone:</td><td class='bold'>$r[dest_phone]</td>";
		$dest .= "<td width='$gutter'></td>";
		$dest .= "<td>Notes:</td><td class='bold'>$r[dest_notes]</td>";
		$dest .= "</tr></table></center>";
		$dest .= "<hr>";
		$body = "Driver must ask for and receive:";
		$body .= "<center><table width='80%'><tr>";
		$body .= "<td>Commodity</td><td>Est Weight</td><td>Size</td><td>Class</td><td>Pallets</td><td>";
		$body .= "</tr><tr>";
		$body .= "<td class='bold heavy_frame'>$r[commodity]</td><td class='bold heavy_frame'>$r[weight]</td><td class='bold heavy_frame'>$r[size]</td><td class='bold heavy_frame'>$r[class]</td><td class='bold heavy_frame'>$r[pallets]</td><td>";
		$body .= "</tr></table></center><br><br>";
		$body .= "Any loading or unloading fees must be negotiated prior to invoicing and driver must get bill signed and obtain a lumper receipt. ALL DRIVERS MUST CALL IN FOR DISPATCH , WHEN LOADED AND EMPTY WITH A VERBAL POD TO INSURE NO PENALTIES. DRIVERS MUST CHECK CALL DAILY WITH DTS BETWEEN THE HOURS OF 7:00AM AND 10:00AM CENTRAL TIME. FAILURE TO MEET ANY OF THE ABOVE REQUIREMENTS WILL RESULT IN A $25.00 PENALTY PER INSTANCE.";
		$body .= "<br><br><center><table width='60%' class='' cellspacing='0'>";
		$body .= "<tr>
					<td></td>
					<td>Amount</td>
					<td>Rate</td>
					<td>Extended</td>
				</tr>";
		$line_haul_total = $this->money($r['line_haul_amount'] * $r['carrier_line_haul']);
		//top right bottom left
		if($line_haul_total > 0)
		{
			$body .= "<tbody style='border:1px solid black'>
					<tr style=''>
						<td class='bold'>Line Haul</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['line_haul_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_line_haul'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$line_haul_total</td>
					</tr>";
		}
		$detention_total = $this->money($r['detention_amount']*$r['carrier_detention']);
		if($detention_total > 0)
		{
			$body .= "	<tr style=''>
						<td class='bold '>Detention</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['detention_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_detention'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$detention_total</td>
					</tr>";
		}
		$tonu_total = $this->money($r['tonu_amount']*$r['carrier_tonu']);
		if($tonu_total > 0)
		{
			$body .= "	<tr style=''>
						<td class='bold '>TONU</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['tonu_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_tonu'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$tonu_total</td>
					</tr>";
		}
		$unload_load_total = $this->money($r['unload_load_amount']*$r['carrier_unload_load']);
		if($unload_load_total > 0)
		{
			$body .= "	<tr style=''>
						<td class='bold '>Unload/Load</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['unload_load_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_unload_load'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$unload_load_total</td>
					</tr>";
		}
		$fuel_total = $this->money($r['fuel_amount']*$r['carrier_fuel']);
		if($fuel_total > 0)
		{
			$body .= "	<tr style=''>
						<td class='bold '>Fuel</td>
						<td class='bold center' style='border:solid black;border-width: 1px 0px 1px 1px;'>".floatval($r['fuel_amount'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 0px 1px 1px;'>$".$this->money($r['carrier_fuel'])."</td>
						<td class='bold right' style='border:solid black;border-width: 1px 1px 1px 1px;'>$$fuel_total</td>
					</tr>";
		}
		$other_total = $this->money($r['other_amount']*$r['carrier_other']);
		if($other_total > 0)
		{
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
		//$carrier .= "<td>Temp Control:</td><td class='bold'>????</td>";
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
						<td rowspan=2>Thank You,<br>$r[booked_name]<br>Domestic Transport Solutions<br>847-981-1400 phone<br>847-981-1411 fax</td></tr>";
		$footer .= "<tr>
						<td valign='bottom'>
							<div style='height:20px;border-bottom:1px solid black;margin:3px'></div>
							Print Name And Title
						</td>
					</tr></table>";
		//$body .= $this->check_size();
		$footer .= "<input type='button' id='print_button' value='Print' onclick='print();'";
		return $header.$origin.$dest.$body.$carrier.$legal.$footer;
		
	}
	function get_order_by_name()
	{
		$sql = 'SELECT username FROM users u, `load` l WHERE u.user_id = l.order_by AND l.load_id = '.$this->load_id;
		$re = db_query($sql);
		$ro = db_fetch_array($re);
		return $ro[0];
	}
	
	function get_pickups($load_id)
	{
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
					AND lwp.load_id = $load_id";
		return db_query($sql);
	}
	function get_acct_owner_name()
	{
		$sql = 'SELECT username
				FROM users u, customer c, `load` l
				WHERE u.user_id = c.acct_owner
				AND c.customer_id = l.customer_id
				AND l.load_id = '.$this->load_id;
		$re = db_query($sql);
		$ro = db_fetch_array($re);
		return $ro[0];
	}
	
	function been_delivered()
	{
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
		$re = db_query($sql);
		$ro = db_fetch_array($re);
		return $ro[0];
	}
}
$l = new load_table();
	echo $l->render();
?>