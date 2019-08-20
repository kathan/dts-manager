<?php
require_once('global.php');
require_once('database.php');
require_once("column.php");
class table
{
	/*
		Field Options:
		-locked (cannot be updated thru view)
		-hidden (will not be shown in view)
		-protected (requires authorization to update)
		-private (requires authorization to view)
		
		Added columns will only be visible in list mode
	*/
	var $columns = [];
	var $other_inputs = [];
	var $tables = [];
	var $resource;
	var $feedback;
	var $hide_column_heads;
	var $orientation;
	var $restricted;
	var $children = [];
	var $parent = [];
	var $name;
	var $form;
	var $save_str = 'Save';
	var $add_str = 'Add';
	var $delete_str = 'Delete';
	var $new_str = 'New';
	var $edit_str = 'Edit';
	var $filter_str = 'filter';
	var $list_str = 'list';
	var $cancel_str = 'Cancel';
	var $table_params = [];
	var $show_delete = true;
	var $show_menu;
	var $show_rel_menu = false;
	var $virtual_columns = [];
	var $sql;
	var $sql_from;
	var $sql_where;
	var $sql_order;
	var $show_row_menu;
	var $date_created_column;
	var $created_by_column;
	var $updated_by_column;
	var $auto_save = false;
	var $submit_input;
	var $error_str;
	var $class ='list sortable';
	var $wildcard_search=false;
	
	function __construct($table=null, $desc=true)
	{
		
		require_once("html_form.php");
		$this->form = new html_form();
		$this->orientation = 'vertical';
		if(!$table)
		{
			$filename = getfilename($_SERVER['SCRIPT_NAME']);
			$filename = explode('.', $filename);
			$this->name = $filename[0];
			$this->tables[] = $filename[0];
		}else
		{
			$this->name = $table;
			$this->tables[] = $table;
		}
		/*$this->sql_select .= "
						SELECT *";
		$this->sql_from .= "
						FROM `$this->name`";*/
		if($desc)
		{
			$this->_describe();
		}
		/*echo "TABLE:".$this->name." ";
		if($relative)
		{
			echo "fast";
		}else{
			echo "slow";
		}
		echo "<br>";*/
	}
	
	function error()
	{
		return $this->error_str;
	}
	
	function add_error($str)
	{
		$this->error_str .= $str."<br>";
	}
	
	function set_submit_input(&$si)
	{
		$this->submit_input =& $si; 
	}
	
	function set_auto_save()
	{
		$this->auto_save = true;
	}
	
	function &get_columns(&$sql)
	{
		$sql .= "SELECT * ";
		foreach($this->virtual_columns as $vt)
		{
			$sql .= ", ".$vt[0]." ";
			if(isset($vt[1]))
			{
				$sql .= " `".$vt[1]."`";
			}
		}
		$sql .= " ";
		return $sql;
	}
	
	function &get_tables(&$sql)
	{
		$sql .= " FROM `$this->name` ";
		return $sql;
	}
	
	function add_virtual_column($column, $alias='')
	{
		if(isset($alias))
		{
			$this->virtual_columns[] = Array($column, $alias);
			
			if(array_key_exists($alias, $this->columns))
			{
				$this->columns[$alias]->insert();
			}
		}else{
			$this->virtual_columns[] = Array($column);
			if(array_key_exists($column, $this->columns))
			{
				//echo $this->columns[$column]->insert();
				$this->columns[$column]->insert();
			}
		}
	}
	
	function show_relationships()
	{
		$this->show_rel_menu = true;
	}
	
	function hide_delete()
	{
		$this->show_delete = false;
	}
	
	function show_menu()
	{
		$this->show_menu = true;
	}
	
	function add_other_inputs(&$input)
	{
		$this->other_inputs[$input->get_name()] =& $input;
	}
	
	
	function add_table_params($key, $value)
	{
		$this->table_params[$key] = $value;
	}
	
	function &get_form()
	{
		return $this->form;
	}
	
	
	
	function set_sql($sql)
	{
		$this->sql = $sql;
	}
	
	function &get_filter(&$sql)
	{
		$i=0;
		//This should be "if $key is in list of column names"
		foreach ($_REQUEST as $key => $value)
		{
			if($key != 'action' && array_key_exists($key, $this->columns) && $value != '')
			{
				if($i == 0)
				{
					$sql .= " WHERE ";
				}else{
					$sql .= " AND ";
				}
				if($this->wildcard_search)
				{
					$value .= '%';
					$value = '%'.$value;
				}
				//If the first or last character is a wild card the create a like statement
				if(substr($value, 1) == '%' || substr($value, -1, 1) == '%')
				{
					$sql .= "$key like ".$this->columns[$key]->format_for_db(html_entity_decode($value));
				}else{
					$sql .= "$key = ".$this->columns[$key]->format_for_db(html_entity_decode($value));
				}
				$i++;
			}
			
		}
		
		//echo $sql;
		//$this->resource = DB::query($this->sql);
		//$this->_scan_resource();
		//$this->_describe();
		return $sql;
	}
	
	function pk_filter($pks)
	{
		$i=0;
		//This should be "if $key is in list of column names"
		foreach ($pks as $pk)
		{
			$pk_name = $pk->get_name();
			if($i == 0)
			{
				$this->sql_where .= " WHERE ";
			}else{
				$this->sql_where .= " AND ";
			}
			$this->sql_where .= "$pk_name = $_REQUEST[$pk_name]";
			$i++;
		}
		//echo $this->sql;
		$this->_describe();
		//$this->_scan_resource();
	}
	
	function get_sql()
	{
		return $this->sql;
	}
	
	function set_tables($tables)
	{
		$this->tables = $tables;
	}
	
	function render_sql(&$sql)
	{
		$this->get_columns($sql);
		$this->get_tables($sql);
		$this->get_filter($sql);
		$this->get_order_by($sql);
	}
	
	function render()
	{
		
			switch(get_action())
			{
				case $this->list_str:
				{
					return $this->_render_list();
					break;
				}
				case $this->edit_str:
				{
					$pk_obj = $this->get_primary_key();
					$id = $_REQUEST[$pk_obj->get_name()];
					
					return $this->_render_edit($this->get_row($id), true);
					break;
				}
				case $this->delete_str:
				{
					$this->delete();
					return $this->_render_list();
					break;
				}
				case $this->save_str:
				{
					$this->update();
					return $this->_render_list();
					break;
				}
				case $this->filter_str:
				{
					$this->set_filter();
					return $this->_render_list();
					break;
				}
				case $this->new_str:
				{
					return $this->_render_edit();
					break;
				}
				case $this->add_str:
				{
					$this->add();
					return $this->_render_list();
					break;
				}
				default:
				{
					return $this->_render_list();
					break;
				}
			}
		
	}
	
	function add()
	{
		if($this->check_form())
		{
			
			global $feedback;
			$field_names = '';
			$values = "";
			$sql = "INSERT INTO `".$this->name ."` (";
			$fields = array_keys(array_merge($_REQUEST, $_FILES));
			//print_r($_FILES);
			foreach($fields as $field)
			{	
				/*if( array_key_exists($field, $this->columns))
				{
					echo $field;
				}*/
				if($field != 'action' && $field != $this->save_str && array_key_exists($field, $this->columns) && ((isset($_REQUEST[$field]) && $_REQUEST[$field] != '') || isset($_FILES[$field])))
				{
					if($field_names != '')
					{
						$field_names .= ', ';
						$values .= ",";
					}
					$field_names .= "$field";
					
					$values .= $this->columns[$field]->format_for_db(safe_get($_REQUEST[$field]));
					//echo safe_get($_REQUEST[$field]);
					if($this->columns[$field]->error_str)
					{
						$this->add_error($this->columns[$field]->error_str);
					}
				}
			}
			$sql .= "$field_names) VALUES ($values)";
			//echo $sql;
			$r = DB::query($sql);
			
			if(DB::error())
			{
				$this->add_error(DB::error());
				$this->add_error($sql);
				$this->add_error('table.add()');
				return false;
			}else{
			
				$this->add_feedback("New record was added.");
				$this->last_id = db_insertid();
				//echo $this->last_id;
				return true;
			}
			$this->_describe();
		}
	}
	
	function get_sort_string(&$column)
	{
		if(isset($_REQUEST['dir']) && $_REQUEST['dir'] == 'asc')
		{
			$opp_dir = 'desc';
		}else{
			$opp_dir = 'asc';
		}
		return "<a href=\"$_SERVER[SCRIPT_NAME]?dir=$opp_dir&order_by=".$column->get_name()."\">".$column->formatted_name()."</a>";
	}
	
	function _render_horizontal_list()
	{
		require_once("action_link.php");
		require_once("edit_link.php");
		require_once("delete_button.php");
		
		$html = "
		$this->feedback
<table class='list'>";
		//build each row in an array;
		$field_count = db_num_fields($this->resource);
		
		$rows = Array($field_count+1);
		
		//====== column heads ======
		
		$col = 0;
		foreach($this->columns as $c)
		{
			if(!$c->is_hidden())
			{
				if($this->hide_column_heads)
				{
					$rows[$col] = '';
				}else{
					$rows[$col] = "
		<td class='list_head'>
				".$this->get_sort_string($c)."
		</td>";
				}
			}
			$col++;
		}
		$rows[$col+1]='';
		//===== End Column Heads ======
		
		while($row = db_fetch_array($this->resource))
		{
			$menu='';
			$col = 0;
			foreach($this->columns as $c)
			{
				if(!$c->is_hidden())
				{
					$rows[$col] .= "
		<td class='list_data'>".
			$c->get_view_html($row[$col]);
					
					if($c->is_primary() && !$this->restricted)
					{
						$rows[$col] .= "<br />";
						$new = new action_link($_SERVER['SCRIPT_NAME'], $this>new_str, $row[$col] , $c->get_name());
						$edit = new edit_link($_SERVER['SCRIPT_NAME'], $row[$col] , $c->get_name());
						$del = new delete_button($_SERVER['SCRIPT_NAME'], $row[$col] , $c->get_name());
						$menu .= $new->render();
						$menu .= $edit->render();
						$menu .= $del->render();
					}
					$menu .= $c->get_child_link($row[$col])."<br />";
					if($c->has_parent())
					{
						$p = $c->get_parent();
						if($this->show_menu)
						{
							$new = new action_link($_SERVER['SCRIPT_NAME'], $this->new_str, $row[$col] , $p->get_name());
							$menu .= $new->render()."<br />";
						}
						
						
						$menu .= $c->get_parent_link($row[$col]);
					}
				$rows[$col] .= "
		</td>";
				}
				$col++;
			}
			$rows[$col+1] .= "<td>$menu</td>";
		}
		
		$col = 0;
		foreach($this->columns as $c)
		{
			
			$html .= "
	<tr class='list_row'>";
			if(!$c->is_hidden())
			{
				$html .= $rows[$col];
			}
			$html .= "
	</tr>";
			$col++;
		}
		$html .= "
	<tr>
		".$rows[$col+1]."
	</tr>
</table>";
		return $html;
	}
	
	function &get_order_by(&$sql)
	{
		$sql .= $this->sql_order;
	}
	function set_order_by($order_by='')
	{
		if(isset($_REQUEST['order_by']))
		{
			$this->sql_order .= " ORDER BY ".$_REQUEST['order_by'];
		}elseif($order_by){
			$this->sql_order .= " ORDER BY $order_by";
		}
		
		if(isset($_REQUEST['dir']))
		{
			$this->sql_order .= " ".$_REQUEST['dir'];
		}
	}
	
	function get_menu()
	{
		require_once("hyperlink.php");
		//$menu = "<span class='table_menu'><a href=\"$_SERVER[SCRIPT_NAME]?action=$this->new_str\">New</a></span>";
		$this->add_table_params('action', $this->new_str);
		$new = new hyperlink($_SERVER['SCRIPT_NAME'], $this->new_str, $this->table_params);
		$menu = $new->render();
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == $this->filter_str)
		{
			$menu .= "<span class='table_menu'><a href=\"$_SERVER[SCRIPT_NAME]\">Show All</a></span>";
		}
		return $menu;
	}
	
	function add_class($class)
	{
		$this->class .= " $class";
	}
	function _render_list()
	{
		$sql ='';
		$this->render_sql($sql);
		/*$this->get_columns($sql);
		$this->get_tables($sql);
		$this->get_filter($sql);
		$this->get_order_by($sql);*/
		$this->resource = DB::query($sql);

		//echo $sql."<br>";
		$this->_scan_resource();
		$edit='';
		if(!$this->hide_column_heads && $this->show_menu)
		{
			$edit .= $this->get_menu();
		}
		if($this->orientation == 'horizontal')
		{
			return $edit . $this->_render_horizontal_list();
		}else
		{
			return $edit . $this->_render_vertical_list();
		}
	}
	
	function _render_vertical_list()
	{
		require_once("action_link.php");
		//require_once("edit_link.php");
		require_once("hyperlink.php");
		require_once("delete_button.php");
		
		$html = "<div>$this->feedback</div>
		<table class='$this->class' id='$this->name'>";

		//====== Row Heads ======
		if(!$this->hide_column_heads)//1
		{
			$html .= "<thead>";
			$html .= "<tr>";
			$col = 0;
		
			foreach($this->columns as $c)//2
			{
				if(!$c->is_omitted())//3
				{
					$html .= "
		<th class='list_head'>"
				.$this->get_sort_string($c)
				//.$c->name
				."
		</th>";
				}// end 3
				$col++;
			}// end 2
			$html .= "</thead>";
			$html .= "</tr>";
		}//end 1
		//====== End Row Heads ======
		
		$i=0;
		$html .= "<tbody>";
		while($row = db_fetch_array($this->resource))//4
		{
			$row_menu='';
			if($i % 2 == 0)//5
			{
				$row_class = "row1";
			}else{//5
				$row_class = "row2";
			}// end 5
			
			//=== get id===
			$pk = $this->get_primary_key();
			$id = $row[$pk->get_name()];
			//========
			$html .= "
	<tr class='$row_class' onclick=\"row_clicked('$id', '".$pk->get_name()."', '$this->name')\">";
			$col = 0;
			//foreach(array_merge($this->columns, $this->virtual_columns) as $c)//6
			foreach($this->columns as $c)//6
			{
				
				if($c->is_primary())//7
				{
					//$html .= "<br />";
					
					//====Edit button=====
					$edit = new hyperlink($_SERVER['SCRIPT_NAME'], $this->edit_str, $this->table_params);
					$edit->add_param("action", $this->edit_str);
					$edit->add_param($c->get_name(), $row[$col]);
					$row_menu .= $edit->render()."<br />";
					//====================
					
					//======Delete button======
					if($this->show_delete)//8
					{
						$del = new delete_button($_SERVER['SCRIPT_NAME'], $row[$col] , $c->get_name());
						$row_menu .= $del->render()."<br />";
					}// end 8
					//=========================
				}
				
				//====Show Children====
				if($this->show_rel_menu)//9
				{
					$row_menu .= $c->get_child_link($row[$col]);
				}// end 9
				//=====================
				
				//====Show parents====
				if($this->show_rel_menu && $c->has_parent())//10
				{
					//Provides links back parents
					$p = $c->get_parent();
						
					//=====New button======
					
						$new = new action_link($_SERVER['SCRIPT_NAME'], $this->new_str, $row[$col] , $p->get_name());
						$row_menu .= $new->render()."<br />";
					
					//=====================
			
					$row_menu .= $c->get_parent_link($row[$col]);
				}//10
				//====================
				
				if(!$c->is_omitted())//11
				{
					//echo $col;
					$html .= "
		<td class='list_data'>
			".$c->get_view_html($row[$col]);
					//former menu
				$html .= "
		</td>";
				}// end 11
				$col++;
				
			}// end 6
			if($this->show_row_menu)
			{
				$html .= "<td class='menu'>$row_menu</td>";
			}
			$html .= "
	</tr>";
		$i++;
		}// end 4
		$html .= "
</table>";
		return $html;
	}
	
	function _render_edit($row=null, $method=null)
	{
		
		$method = safe_get($_SERVER['SCRIPT_NAME']);
		
		//====get other inputs====
		$oi_names = array_keys($this->other_inputs);
		foreach($oi_names as $oi_name)
		{
			$this->form->add_input($this->other_inputs[$oi_name]);
		}
		//=========================
		$col = 0;
		$fields = array_keys($this->columns);
		
		foreach($fields as $c)
		{
			if(!$this->columns[$c]->is_omitted())
			{
				if($row != null && isset($row[$c]))
				{
					$this->form->add_input($this->columns[$c]->get_edit_html($row[$c]));
				}elseif(isset($_REQUEST[$c])){
					$this->form->add_input($this->columns[$c]->get_edit_html($_REQUEST[$c]));
				}else{
					$this->form->add_input($this->columns[$c]->get_edit_html());
				}
			}
			$col++;
		}
		if($row != null)
		{
			//If there is no row of data set the action to create a new row
			$this->form->create_submit_input($this->save_str, 'action');
		}elseif(isset($this->submit_input))
		{
			$this->form->add_input($this->submit_input);
		}else{
			//If there is a row of data set the action to update that row
			$this->form->create_submit_input($this->add_str, 'action');
		}
		
		//$this->form->create_submit_input($this->cancel_str, 'cancel');
		return 	$this->feedback.
				$this->form->render();
	}
	
	function add_input(&$input)
	{
		$this->form->add_input($input);
	}
	
	function &get_primary_key(){
		$column_keys = array_keys($this->columns);
		foreach($column_keys as $key){
			$column =& $this->columns[$key];
			if($column->is_primary()){
				return $this->columns[$key];
			}
		}
	}
	
	function get_primary_keys()
	{
		$column_keys = array_keys($this->columns);
		$pk = [];
		foreach($column_keys as $key)
		{
			$column =& $this->columns[$key];
			if($column->is_primary())
			{
				$pk[] = $this->columns[$key];
			}
		}
		return $pk;
	}
	
	function _scan_resource()
	{
		

		for($c=0; $c < db_num_fields($this->resource); $c++)
		{
			$field_name = db_fieldname($this->resource, $c);
			if(!isset($this->columns[$field_name]))
			{
				$column = new column($this,$field_name);
				$this->columns[$field_name] = $column;
			}	
		}
		//echo $this->sql;
	}
	
	function get_name()
	{
		return $this->name;
	}
	
	function _describe()
	{
		$sql = "Describe `$this->name`";
		$r = DB::query($sql);
		if(DB::error())
		{
			$this->add_error(DB::error());
			$this->add_error($sql);
			$this->add_error('table._describe');
		}else{
			while($row = DB::fetch_assoc($r))
			{
				if(!isset($this->columns[$row['Field']]))
				{
					//$column = new column($this, $row['Field']);
					$column = new column($this, $row['Field'], false);
					$search = '/(\w+)(\((\d+)\))?/';
					preg_match($search, $row['Type'], $result);
					if(isset($result[1]))
					{
						$column->type = $result[1];
					}else{
						$column->type = 'unknown';
					}
			
					if(isset($result[3]))
					{
						$column->length = $result[3];
					}
					switch($row['Key'])
					{
						case 'PRI':
							$column->is_primary = true;
							break;
						case 'UNI':
							$column->is_unique = true;
							break;
					}
					if($row['Null'] == 'NO')
					{
						$column->not_null = true;
					}
					if($row['Extra'] == 'auto_increment')
					{
						$column->auto_inc = true;
					}
					if($column->error_str)
					{
						$this->add_error($column->error_str);
					}
					$this->columns[$row['Field']] = $column;
				}
			}
		}
	}
	function declare_column($col_name, $type, $length=0, $is_unq=false, $is_pk=false, $auto_inc=false)
	{
		$column = new column($this, $col_name, false);
		$column->type = $type;
		$column->length = $length;
		$column->is_primary = $is_pk;
		$column->is_unique = $is_unq;
		$column->auto_inc = $auto_inc;
		$this->columns[$name] =& $column;
	}
	function update()
	{
		
		//$pk_obj = $this->get_primary_key();
		$pks = $this->get_primary_keys();
		
		//$pk = $pk_obj->get_name();
		$set = '';
		$sql = "UPDATE `".$this->name."`
		SET ";
		$fields = array_keys($this->columns);
		
		foreach($fields as $field)
		{
			if($field != 'action' && isset($_REQUEST[$field]) &&  !in_array($field, $pks))
			{
				if(!$set == '')
				{
					$set .= ', ';
				}
				$set .= "$field = ";
				
				$set .= $this->columns[$field]->format_for_db(safe_get($_REQUEST[$field]));
			}
		}
		$sql .= $set;
		$clause = 'WHERE';
		foreach($pks as $pk_obj)
		{
			$pk = $pk_obj->get_name();
			$sql .= " $clause $pk = ".$pk_obj->format_for_db($_REQUEST[$pk]);
			$clause = 'AND';
			//$id_list .= $_REQUEST[$pk].
		}
		//$sql .= " WHERE $pk = ".$pk_obj->format_for_db($_REQUEST[$pk]);//single pk only
		DB::query($sql);
		//echo "$sql<br>";
		//$this->add_feedback($sql);
		if(DB::error())
		{
			$this->add_error(DB::error());
			$this->add_error($sql);
			$this->add_error('table.update');
			return false;
		}else{
			$this->add_feedback("ID $_REQUEST[$pk] was updated.");
			return true;
		}
	}
	
	function delete()
	{
		//$pk_obj = $this->get_primary_key();
		$pks = $this->get_primary_keys();
		
		$sql = "DELETE FROM ".$this->name;
		$clause = 'WHERE';
		foreach($pks as $pk_obj)
		{
			$pk = $pk_obj->get_name();
			$sql .= " $clause $pk = ".$pk_obj->format_for_db($_REQUEST[$pk]);
			$clause = 'AND';
			//$id_list .= $_REQUEST[$pk].
		}
		$result = DB::query($sql);
		if(DB::error())
		{
			$this->add_error(DB::error());
			$this->add_error($sql);
			$this->add_error('table.delete');
			return false;
		}else{
			$this->add_feedback("ID $_REQUEST[$pk] was deleted.");
			return true;
		}
	}
	
	
	function get_row($id=null, $assoc=false)
	{
		//needs to be updated for multiple column primary keys
		$sql = "select * from `$this->name`";
		$pk_obj = $this->get_primary_key();
		$pk = $pk_obj->get_name();
		if(isset($id))
		{
			$sql .= " where $pk = ".$pk_obj->format_for_db($id);
		}else{
			$sql .= " limit 0";
		}
		$this->sql = $sql;
		$this->resource =  DB::query($sql);
		if(DB::error())
		{
			$this->add_error(DB::error());
			$this->add_error($sql);
			$this->add_error('table.get_row()');
		}elseif($assoc){
			return DB::fetch_assoc($this->resource);
		}else{
			return DB::fetch_array($this->resource);
		}
	}
	
	function omit_column($column_name)
	{
		if(isset($this->columns[$column_name]))
		{
			$this->columns[$column_name]->omit();
		}
	}
	
	function insert_column($column_name)
	{
		if(isset($this->columns[$column_name]))
		{
			$this->columns[$column_name]->insert();
		}
	}
	
	function omit_all_columns()
	{
		$columns_names = array_keys($this->columns);
		foreach($columns_names as $column_name)
		{
			$this->columns[$column_name]->omit();
		}
	}
	
	function hide_all_columns()
	{
		$columns_names = array_keys($this->columns);
		foreach($columns_names as $column_name)
		{
			$this->columns[$column_name]->hide();
		}
	}
	
	function hide_column($column_name)
	{
		if(isset($this->columns[$column_name]))
		{
			$this->columns[$column_name]->hide();
		}
	}
	
	function unhide_column($column_name)
	{
		if(isset($this->columns[$column_name]))
		{
			$this->columns[$column_name]->unhide();
		}
	}
	
	function add_reg_exp($column_name, $pattern, $replacement)
	{	
		$this->columns[$column_name]->add_reg_exp($pattern, $replacement);
	}
	
	function hide_column_heads()
	{
		$this->hide_column_heads = true;
	}
	
	function &get_column($column_name)
	{
		
		return $this->columns[$column_name];
	}
	
	function restrict_edit($restricted)
	{
		$this->restricted = $restricted;
	}
	
	function add_feedback($feedback)
	{
		$this->feedback .= "$feedback<br />"; 
	}
	
	function filter_params_to_query()
	{
		$keys = array_keys($_REQUEST);
		$str ="";
		foreach($keys as $field)
		{
			if($field != 'action' && $field != $this->save_str)
			{
				
				$str .= "&$field=".$_REQUEST[$field];
			}
		}
		return $str;
	}
	
	function set_as_password($column_name)
	{
		if(isset($this->columns[$column_name]))
		{
			$this->columns[$column_name]->set_as_password();
		}
	}
	
	function check_form()
	{
		$failure = false;
		$fields = array_keys($_REQUEST);
		foreach($fields as $field)
		{
			if($field != 'action' && $field != $this->save_str && array_key_exists($field, $this->columns))
			{
				$c = $this->columns[$field];
				if(!$c->check_input($_REQUEST[$field]))
				{
					$failure = true;
					$this->add_error($c->feedback);
					$this->add_error($c->error_str);
				}
			}
		}
		
		return !$failure;
	}
	
	function set_label($column, $label)
	{
		$c =& $this->get_column($column);
		$c->set_label($label);
	}
	
	function set_date_created_column($c)
	{
		$this->date_created_column = $c;
	}
	function set_created_by_column($c)
	{
		$this->created_by_column = $c;
	}
	function set_updated_by_column()
	{
		$this->updated_by_column = $c;
	}
	//================ Deprecated =======================
	
	
}
?>