<?php
// Version 2
require_once('DB_Table.php');
require_once('Paginator.php');
require_once('Request.php');
class DB_Editor extends DB_Table{
	var $edit_str = 'edit';
	var $new_str = 'new';
	var $start_str = 'start';
	var $search_str = 'Search';
	var $no_delete; //disallows deletes
	var $no_view = [];//Not in list view, visible and editable in edit view
	var $no_edit = [];//Visible in list view, visible but not editable in edit
	var $hidden_edit = [];//Visible in list view, hidden in edit view
	var $no_access = [];//Not visible in list view, not in edit view
	var $list_limit = 20;
	var $edit_html=[];
	var $additional_inputs=[];
	var $additional_columns=[];
	var $sql_columns=[];
	var $select=[];
	var $where=[];
	var $debug;
	var $row;
	var $list_columns;
	var $list_template;
	var $list_sql;
	var $edit_template;
	
	function __construct($table=null){
		//Version::add(__FILE__, .1);
		self::__construct($table);
	}
	
	function set_list_columns($cols){
		$this->list_columns = $cols;
	}
	
	function set_select($col, $select){
		$this->select[$col] = $select;
	}
	
	function add_column($val, $alias=''){
		
		if(isset($alias)){
			$this->columns[$alias] = new column($this, $val);
			$this->columns[$alias]->alias = $alias;
		}else
		{
			$this->columns[$val] = new column($this, $val);
		}
	}
	
	function set_sql($col, $sql){
		$this->sql_columns[$col] = $sql;
	}
	function set_password($col){
		$this->columns[$col]->pw = true;
	}
	function hide_edit($name){
		 $this->hidden_edit[] = $name;
	}
	function add_input($name, $html_input, $sql=''){
		$this->additional_inputs[$name] = $html_input;
		$this->additional_columns[$name] = $sql;
	}
	function no_delete(){
		$this->no_delete = true;
	}
	function hide($name){
		return $this->columns[$name]->hide();
	}
	
	function execute(){
		if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){
			if(parent::execute()){
				
				if(isset($_POST['referer'])){
					//header("Location: $_REQUEST[referer]");
				}else{
					//$c .= $this->list_records();
				}
			}else{
				//echo $this->error_str;
				//$c .= $this->edit();
			}
	
		}
			$this->debug ? $this->add_feedback("action:".Request::get_action()) : '';
			switch($_GET['action']){
				case $this->new_str:
					$c .= $this->edit();
					break;
				case $this->edit_str:
				
					$c .= $this->edit();
					break;
				case $this->search_str:
					$c .= $this->list_records($_GET[$this->search_str]);
					break;
				default:
					$c .= $this->list_records();
					break;
			}
			
			return $c;
		
	}
	
	function set_edit_html($col, $html){
		$this->edit_html[$col] = $html;
	}
	function no_edit($col_name){
		$this->no_edit[] = $col_name;
	}
	
	function no_access($col_name){
		$this->no_access[] = $col_name;
	}
	
	function no_view($col_name){
		$this->no_view[] = $col_name;
	}
	
	function get_edit_html(&$col, $value=null){
		
		if(isset($col->parent_column)){
			require_once("select_input.php");
			return new select_input($col->get_name(), $col->parent_column->get_name(), $col->parent_label_column, $col->get_parent_records(), $value);
			
		}elseif(array_key_exists($col->get_name(), $this->select)){
			require_once("select_input.php");
			//$name, $id_name, $label_name, $options, $selected=null
			$i = new select_input($col->get_name(), $col->get_name(), $col->get_name(), $this->select[$col->get_name()],$value);
			return $i;
		}else{
			//echo $col->get_type();
			if($col->pw){
				require_once("password_input.php");
				return new password_input($col->get_name(), true);
			}elseif($col->hidden){
				//This should handle and date formats as well
				return $col->get_hidden($value);
			}elseif(array_key_exists($col->get_name(), $this->edit_html)){
				return $this->edit_html[$col->get_name()];
			}else{
			
				switch($col->get_type()){
					case 'date':
						require_once("date_input.php");
						$i = new date_input($col->get_name(), $col->MySQL_Date_To_format($value, 'n/j/Y'));
						if($col->table_obj->auto_save){
							$i->set_id($col->id);
						}
						$i->set_label($col->label);
						return $i;
						break;
					case 'datetime':
						require_once("datetime_input.php");
						$i =  new datetime_input($col->get_name(), $col->MySQL_Date_To_format($value, 'n/j/Y g:i a'));
						$i->set_label($col->label);
						if($col->table_obj->auto_save){
							$i->set_id($col->id);
						}
						return $i;
						break;
					case 'timestamp':
						require_once("datetime_input.php");
						$i =  new datetime_input($col->get_name(), $col->MySQL_Date_To_format($value, 'n/j/Y g:i a'));
						$i->set_label($col->label);
						if($col->table_obj->auto_save){
							$i->set_id($col->id);
						}
						return $i;
						break;
					case 'binary':
						require_once('checkbox_input.php');
						$i =  new checkbox_input($col->get_name(), $value);
						$i->set_label($col->label);
						if($col->table_obj->auto_save){
							$i->set_id($col->id);
						}
						return $i;
						break;
					case 'tinyint':
						require_once('checkbox_input.php');
						$i =  new checkbox_input($col->get_name(), $value);
						$i->set_label($col->label);
						if($col->table_obj->auto_save){
							$i->set_id($col->id);
						}
						return $i;
						break;
					case 'bit':
						require_once('checkbox_input.php');
						$i =  new checkbox_input($col->get_name(), $value);
						$i->set_label($col->label);
						if($col->table_obj->auto_save){
							$i->set_id($col->id);
						}
						return $i;
						break;
					case 'mediumtext':
						require_once('textarea_input.php');
						$i =  new textarea_input($col->get_name(), $value);
						if(isset($col->rows)){
							$i->set_rows($col->rows);
						}
						if(isset($col->cols)){
							$i->set_cols($col->cols);
						}
						$i->set_label($col->label);
						if($col->table_obj->auto_save){
							$i->set_id($col->id);
						}
						return $i;
						break;
					case 'mediumblob':
						require_once("file_input.php");
						$i =  new file_input($col->get_name());
						$i->set_label($col->label);
						if($col->table_obj->auto_save){
							$i->set_id($col->id);
						}
						return $i;
						break;
					case 'longblob':
						require_once("file_input.php");
						$i =  new file_input($col->get_name());
						$i->set_label($col->label);
						if($col->table_obj->auto_save){
							$i->set_id($col->id);
						}
						return $i;
						break;
					default:
						if(isset($col->value_list)){

							return new select_input($col->get_name(), '', $col->get_name(), $col->value_list, $value);
						}else{
							
							require_once('text_input.php');
							$i =  new text_input($col->get_name(), $value);
							if(isset($col->length)){
								if($col->table_obj->auto_save){
									$i->set_id($col->id);
								}
								if($col->length < 30){
									$i->set_attribute('size', $col->length);
								}
								$i->set_attribute('maxlength', $col->length);
							}
							$i->set_label($col->label);
						}
						return $i;
						break;
				}
			}
		}
	}

	function new_button(){
		return "<a href=\"$_SERVER[SCRIPT_NAME]?action=$this->new_str&amp;".Request::recycle_get()."\">New</a>";
	}
	
	function list_button(){
		return "<a href=\"$_SERVER[SCRIPT_NAME]?".Request::recycle_get()."\">List</a>";
	}
	
	function get_row(){
		$pks = $this->get_primary_keys();
		$col_list .= '';
		foreach($this->columns as $col){
			if(!in_array($col->get_name(), $this->no_view)){
				($col_list != '' ? $col_list .= ',':'');
				$col_list .= "".$col->get_column_name()."";
			}
		}
		$sql = "SELECT $col_list FROM `".$this->get_name()."` ";
		
		$clause = 'WHERE';
		foreach($pks as $pk){
			if(!isset($_REQUEST[$pk->get_name()])){
				$this->add_error("Primary key not set. Missing ".$pk->get_name());
				return false;
			}
			$sql .= " $clause `".$pk->get_name()."` = ".$pk->format_for_where($_REQUEST[$pk->get_name()]);
			$clause = 'AND';
		}
		$re = App::$db->query($sql);
		
		$this->row = $re->fetch(PDO::FETCH_ASSOC);
		return $this->row;
	}
	function edit(){
		if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){
			$row = $_POST;
		}else{
			$row = $this->get_row();
		}
		return $this->edit_form($row);
	}

	function set_where($w){
		$this->where[] = $w;
	}
	
	function set_query_limit(){
		is_int($_REQUEST[$this->start_str]) ? $start = $_REQUEST[$this->start_str] : $start = 0;
		return " LIMIT $start, ".$this->list_limit;
	}
	
	function list_records($filter=''){
		if(file_exists($this->list_template)){
			return $this->get_template($filter);
		}else{
			return $this->build_list($filter);
		}
	}
	
	function get_template($filter=''){
		isset($this->list_sql) ? $sql = $this->list_sql : $sql = $this->build_query($filter);
		$t = new Template();
		is_int($_GET[$this->start_str]) ? $start = $_GET[$this->start_str]: $start = 0;
		$start < $this->list_limit ? $prev_start = 0 : $prev_start = $start - $this->list_limit;
		isset($_REQUEST['referer']) ? $referer = $_REQUEST['referer'] : $referer = $_SERVER['HTTP_REFERER'];
		$t->assign('referer', $referer);
		
		$t->assign('search', $_REQUEST[$this->search_str]);
		
		$list = App::$db->query($sql);
		$p = new Paginator($list, $start);
		$t->assign('pag', $p->get());
		$t->assign('list',  $p->to_array($list));

		return $t->fetch($this->list_template);
	}
	
	function build_query($filter=''){
		foreach($this->columns as $col){
			if(!in_array($col->get_name(), $this->no_view)){
				($col_list != '' ? $col_list .= ',':'');
				
				$col_list .= "".$col->get_column_name()."";
				
			}
		}
		$sql = "SELECT $col_list FROM `".$this->get_name()."` ";
		$clause = 'WHERE';
		if($filter != ''){
			
			foreach($this->columns as $col){
				if($col->actual && $col->check_value($filter)){
					($col->use_like() ? $op='like':$op = '=');
				
					$sql .= "$clause `".$col->get_name()."` $op ".$col->format_for_db($filter).' ';
					$clause = 'OR';
				}
			}
			
		}
		foreach($this->where as $w){
			$sql .= "$clause $w";
			$clause = 'AND';
		}
		
		$sql .= "ORDER BY ";
		$i=0;
		$pks = $this->get_primary_keys();
		foreach($pks as $pk){
			$i > 0 ? $sql .= ',' : '';
			$sql .= $pk->get_name();
			$i++;
		}
		return $sql;
	}
	
	function build_list($filter=''){
		$sql = $this->build_query($filter);
		
		$re = App::$db->query($sql);
		$result_count = $re->rowCount();
		$c .= $this->search_form();
		
		$c .= $this->new_button();
		$c .= $this->list_button();
		
		$c .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"".$this->get_name()."\" border=\"0\"><tr>\n";
		$pks = $this->get_primary_keys();
		
		
		for($col = 0; $col <= DB::num_fields($re);$col++){
			
			if(!isset($this->list_columns) || in_array(DB::field_name($re, $col), $this->list_columns)){
				$c .= "<th class=\"".DB::field_name($re, $col)."\">".DB_Editor::format_name(DB::field_name($re, $col))."</th>\n";
			}
		}
		$c .= "</tr>\n";
		$r=1;
		(isset($_GET[$this->start_str]) ? $start = $_GET[$this->start_str]: $start = 0);
		$next_start = $start + $this->list_limit;
		(($result_count - $start) > $this->list_limit ? $next_group_count = $this->list_limit :$next_group_count =($result_count - $start));
		DB::data_seek($re, $start);
		while($row = $re->fetch(PDO::FETCH_NUM)){
			$q_str='';
			$q_input='';
			foreach($pks as $pk){
				$q_str .= "&amp;".$pk->get_name()."=".$row[$pk->get_name()]."&amp;".Request::recycle_get();
				$q_input .= "<input type=\"hidden\" name=\"".$pk->get_name()."\" value=\"".$row[$pk->get_name()]."\" />".Request::recycle_post();
			}
			$r % 2 == 0 ? $class = 'alt-row' : $class = 'row';
			
			$r++;
			$c .= "<tr class=\"$class\">\n";
			for($col = 0; $col < DB::num_fields($re);$col++){
				if(!isset($this->list_columns) || in_array(DB::field_name($re, $col), $this->list_columns)){
					$c .= "<td class=\"".DB::field_name($re, $col)."\"><a href=\"?action=edit$q_str\">".$row[$col]."</a></td>\n";
				}
			}
			
			//=== Delete button ===
			$del_str = DB_Table::$delete_str;
			
			$c .= "<td>";
			$c .= "
					<form id=\"del_$r\" method=\"post\" >
						$q_input
						<input type=\"hidden\" name=\"action\" value=\"$del_str\">
					</form>
					<input type=\"button\" value=\"$del_str\" onclick=\"confirm_delete('del_$r')\" />";
			
			$c .= "</td></tr>\n";
			
			
			if($r > $this->list_limit){
				
				$footer .= "<a href=\"?".Request::recycle_get()."&amp;$this->start_str=$next_start\">Next $next_group_count</a>";
				break;
			}
			
			$c .= "</td>";
			$c .= "</tr>";
		}
		Request::set_recycle('action', $_GET['action']);
		Request::set_recycle($this->search_str, Request::safe_get($_GET[$this->search_str]));
		$c .= "</table>";
		($start < $this->list_limit ? $prev_start = 0 : $prev_start = $start - $this->list_limit);
		if($start > 0){
			$c .= "<a href=\"?".Request::recycle_get()."&amp;$this->start_str=$prev_start\">Prev $this->list_limit</a> ";
		}
		($result_count == 0 ? $c .= "No records found." : $c .= ($start+1)." thru ".($start+$r-1)." of $result_count $footer");
		
		
		return $c;
	}
	
	function new_record(){
		return $this->edit_form();
	}
	
	function action_button($action){
		return "<input type=\"submit\" name=\"action\" value=\"$action\" />";
	}
	
	function get_hidden(&$col, $value=null){
		require_once("hidden_input.php");
		return new hidden_input($col->get_name(), $value);
	}
	
	public static function format_name($name){
		return str_replace(' ', '&nbsp;' ,ucwords(str_replace('_', ' ', $name)));
	}
	
	
	function get_edit_template($row=null){
		$t = new Template();
		$t->assign('row', $row);
		return $t->fetch($this->edit_template);
	}
	
	function edit_form($row=null){
		if(file_exists($this->edit_template)){
			return $this->get_edit_template($row);
		}else{
			return $this->get_edit_form($row);
		}
		
	}
	
	function get_edit_form($row=null){
		//print_r($row);
		//$no_view: Not in list view, visible and editable in edit view
		//$no_edit: Visible in list view, visible but not editable in edit
		//$hidden_edit: Visible in list view, hidden in edit view
		//$no_access: Not in list view, not in edit view
		$c = $this->feedback;
		$c .= $this->error_str;
		$pks = $this->get_primary_keys();
		foreach($pks as $pk){
			$q_str .= "&amp;".$pk->get_name()."=".$row[$pk->get_name()];
			$q_input .= "<input type=\"hidden\" name=\"".$pk->get_name()."\" value=\"".$row[$pk->get_name()]."\" />".Request::recycle_post();
		}
		$c .= DB_Editor::format_name($this->get_name()).":".DB_Editor::format_name($_GET['action'])."<br />";
		$c .= "
		<form method=\"post\" id=\"".$this->name."\">";
		
		isset($_REQUEST['referer']) ? $referer = $_REQUEST['referer'] : $referer = $_SERVER['HTTP_REFERER'];
		
		$c .= "<input type=\"hidden\" name=\"referer\" value=\"$referer\" />";
		$c .= Request::recycle_post()."
			<table>";
		$c .= "
			";
		$cols = $this->columns;
		
		
		foreach($cols as $col){
			if(!in_array($col->get_name(), $this->no_access)){
				
				if(!$col->auto_inc && !in_array($col->get_name(), $this->no_edit)){
					$c .= "
				<tr>";
					if(!$col->is_hidden()){
						$c .= "
					<td>".DB_Editor::format_name($col->get_name()).":</td>";
					}
					$c .= "
					<td>".$this->get_edit_html($col, $row[$col->get_name()])->render()."</td>";
					$c .= "
				</tr>
				";
					
				}else{
					//if(!in_array($col->get_name(), $this->no_edit)){
						$c .= "
				<tr>";
						
						$c .= "
					<td>".DB_Editor::format_name($col->get_name()).":</td>";
					//}
					//print_r($row);
					$c .= "
					<td>".(is_array($row) && !in_array($pks, $row) || in_array($col->get_name(), $this->no_edit) ? $row[$col->get_name()].$this->get_hidden($col, $row[$col->get_name()])->render() : 'Auto')."</td>";
					if(!in_array($col->get_name(), $this->no_edit)){
						$c .= "
				</tr>";
					}
				}
			}
		}

		foreach($this->additional_inputs as $input_key => $input){
			$input->set_attribute('value', $row[$input_key]);
			$c .= "
				<tr>
					<td>".DB_Editor::format_name($input_key).":</td>
					<td>".$input->render()."</td>
				</tr>";
		}
		$c .= "
				<tr>
					<td>";
		//if(isset($row))
		
		if(get_action() == $this->edit_str){
			$c .= $this->action_button(DB_Table::$save_str);
			
		}else{
			$c .= $this->action_button(DB_Table::$add_str);
		}
		$del_str = DB_Table::$delete_str;
		$c .= <<<qq
					</td>
				</tr>
			</table>
		</form>
qq;
		isset($referer) ? $c .= "<input type=\"button\" onclick=\"window.location='$referer'\" value=\"Exit\">" : '';
		if(isset($row)){
			!$this->no_delete ? $c .= "
		<form method=\"post\" action=\"\">$q_input<input type=\"submit\" name=\"action\" value=\"$del_str\" />" :'';
			isset($_SERVER['HTTP_REFERER']) ? $c .= "<input type=\"hidden\" name=\"referer\" value=\"$_SERVER[HTTP_REFERER]\" />" : '';
			$c .= "</form>";
		}
		
		return $c;
	}
	
	function search_form(){
		$c = 'Search';
		$c .= "<form method=\"get\" action=''>".Request::recycle_post()."<input type=\"text\" name=\"$this->search_str\" value='".Request::safe_get($_GET[$this->search_str])."' /><input type='submit' name='action' value='$this->search_str' /></form>";
		return $c;
	}
}

?>
<script type="text/javascript">
	function confirm_delete(del_form){
		if(confirm('Are you sure you want to delete this record?')){
			var f = document.getElementById(del_form);
			f.submit();
		}
	}
</script>