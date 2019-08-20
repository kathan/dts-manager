<?php
require_once("select_input.php");

class view{
	var $tables = [];
	var $row_action;
	var $sql;
	var $table;
	var $primary_key;
	var $value_lists = [];
	var $element_ids = [];
	var $element_on_change = [];
	var $class ='view';
	var $hidden_columns = [];
	var $edit_columns = [];
	var $id;
	var $tableContainer_height='295px';
	var $tbody_height='250px';
	
	function __construct($sql='', $binds=null){
            $this->sql = $sql;
            $this->binds = $binds;
	}
	
	function set_id($id){
            $this->id = $id;
	}
	
	function set_edit_column($col){
            $this->edit_columns[$col] = $col;
	}
	
	function set_value_list($col, $vl){
            $this->value_lists[$col] = $vl;
	}
	
	function set_element_on_change($col, $val){
            $this->element_on_change[$col] = $val;
	}
	
	function set_element_id($col, $id){
            $this->element_ids[$col] = $id;
	}
	
	function hide_column($col){
            $this->hidden_columns[$col] = $col;
	}
	
	function add_class($class){
            $this->class .= " $class";
	}
	
	function set_table($table){
		$this->table = $table;
	}
	
	function set_sql($sql, $binds){
            $this->sql = $sql;
            $this->binds = $binds;
	}
	
	function hide_primary_key(){
	}
	
	function format_label($label){
		return str_replace(' ', '&nbsp;', ucwords(str_replace('_', ' ', $label)));
	}
	
	function set_primary_key($primary_key){
		$this->primary_key = $primary_key;
	}
	
	function set_row_action($row_action){
		$this->row_action = $row_action;
	}
	
	function set_class($class){
		$this->class = $class;
	}
	
	function get_tables(){
		
	}
	
	function render($hide_column_heads=false){
		$res = DB::query($this->sql, $this->binds);
		
		if(DB::error()){
			echo $this->sql;
			echo DB::error();
		}
		$pk = $this->primary_key;
		
		//$html = $this->get_styles();
		$html = "
		<div class='tableContainer' id='tableContainer' style='height: $this->tableContainer_height;overflow: auto;margin: 0 auto;'>
			<table class='$this->class scrollTable' id='$this->id' style='width: 99%;border: none;background-color: #f7f7f7;'>\n";

		//====== Row Heads ======
		if(!$hide_column_heads){
			$html .= "<thead>\n";
			//$html .= "<tr style='position:relative;top: expression(offsetParent.scrollTop);'>\n";
			$html .= "<tr>";
			
			$col_count = DB::num_fields($res);
			for($c=0; $c < $col_count; $c++){
				$col_name = DB::field_name($res, $c);
				//var_dump($col_name);
				if(!array_key_exists($col_name, $this->hidden_columns)){
					$html .= "<th>".$this->format_label($col_name)."</th>\n";
				}
			}// end 2
			$html .= "</tr>\n";
			$html .= "</thead>\n";
			
		}//end 1
		//====== End Row Heads ======
		$html .= "<tbody style='overflow: auto;overflow-x: hidden;'>\n";
		//$html .= "<tbody style='overflow: auto;height: $this->tbody_height;overflow-x: hidden;'>\n";//Causes IE to make each row the height of tbody
		$i=0;
		//==== rows ====
		
		while($row = DB::fetch_array($res)){
			if(isset($pk)){
				$id = $row[$pk];
			}else{
				$id='';
			}
			
			if(isset($this->table)){
				$table = $this->table;
			}else{
				$table = '';
			}
			
			if($i % 2 == 0){
				$row_class = "normalRow";
			}else{//5
				$row_class = "altRow";
			}// end 5
			
			//========
			if(isset($this->row_action)){
				$row_onclick = eval("return ".$this->row_action);
				$html .= "
	<tr id='".$this->table."_$id' class='$row_class' onclick=\"$row_onclick\">\n";
			}else{
				$html .= "
	<tr id='".$this->table."_$id' class='$row_class'>\n";
			}
						
			//==== columns ====
			
			for($c=0; $c < $col_count; $c++){
				$col_name = DB::field_name($res, $c);
				if(!array_key_exists($col_name, $this->hidden_columns)){
					$html .= "<td class='list_data $col_name' style='
						padding-right: 2px;
						text-align: left;
						font-weight:bold;
						font-size: 12px;
						color: black;'>";
					//print($this->value_lists[$col_name]);
					
					if(isset($this->value_lists[$col_name])){
						//echo $row[$c];
						$si = new select_input($col_name, $col_name, $col_name, $this->value_lists[$col_name], $row[$c]);
						if(isset($this->element_ids[$col_name])){
							//$row_onclick = eval("return ".$this->row_action);
							$el_id = eval("return ".$this->element_ids[$col_name]);
							$si->set_id($el_id);
						}
						if(isset($this->element_on_change[$col_name]))
						{
							$oc = $this->element_on_change[$col_name];
							$si->add_attribute('onchange', $oc);
						}
						$html .= $si->render();
					}elseif(array_key_exists($col_name, $this->edit_columns)){
						
						$e = new text_input($col_name, $row[$c]);
						if(isset($this->element_ids[$col_name]))
						{
							//$row_onclick = eval("return ".$this->row_action);
							$el_id = eval("return ".$this->element_ids[$col_name]);
							$e->set_id($el_id);
						}
						if(isset($this->element_on_change[$col_name]))
						{
							$oc = $this->element_on_change[$col_name];
							$e->add_attribute('onchange', $oc);
						}
						$html .= $e->render();
					}else{
						
						$html .= $row[$c];
					}
					$html .= "</td>\n";
				}
				
			}// end 6
			//==== end columns ====
			$html .= "</tr>\n";
			$i++;
		}// end 4
		//==== end rows ====
		$html .= "</tbody>\n</table>\n</div>
		<style>
			.normalRow
			{
				background-color:white;
				color:color;
			}
			.altRow
			{
				background-color:silver;
				color:color;
			}
		</style>\n";
		return $html;
	}

	function get_styles()
	{
		return "<style type='text/css'>
/*==== Scrollable List ====*/
div.tableContainer {
	width: 65%;		/* table width will be 99% of this*/
	height: 295px; 	/* must be greater than tbody*/
	overflow: auto;
	margin: 0 auto;
	}

table {
	width: 99%;		/*100% of container produces horiz. scroll in Mozilla*/
	border: none;
	background-color: #f7f7f7;
	}
	
table>tbody	{  /* child selector syntax which IE6 and older do not support*/
	overflow: auto; 
	height: 250px;
	overflow-x: hidden;
	}
	
thead tr	{
	position:relative; 
	top: expression(offsetParent.scrollTop); /*IE5+ only*/
	
	}
	
thead td, thead th {
	text-align: center;
	font-size: 14px; 
	background-color: oldlace;
	color: steelblue;
	font-weight: bold;
	border-top: solid 1px #d8d8d8;
	}	
	
td	{
	color: #000;
	padding-right: 2px;
	font-size: 12px;
	text-align: right;
	
	border-left: solid 1px #d8d8d8;
	border-bottom:1px solid black;
	}

tfoot td	{
	text-align: center;
	font-size: 11px;
	font-weight: bold;
	background-color: papayawhip;
	color: steelblue;
	border-top: solid 2px slategray;
	}

td:last-child {padding-right: 20px;} /*prevent Mozilla scrollbar from hiding cell content*/
/*==========================*/
</style>";
	}
}
?>