<?php
require_once("dts_table.php");
require_once("includes/hidden_input.php");
require_once("includes/portal.php");
require_once("includes/submit_input.php");

class carrier_table extends dts_table{
	var $carrier_id;
	function __construct(){
		parent::__construct('carrier');
		$this->prefix='S';
		$this->hide_delete();
		$this->hide_column('carrier_id');
		$this->add_table_params('page', 'carrier');
			
		$i = new hidden_input('page', 'carrier');
		$this->add_other_inputs($i);
		
		
		$c =& $this->get_column('carrier_rep');
		$c->set_value_list($this->get_users());
		
		$this->tab_menu->add_tab("?page=carrier", 'List');
		$this->tab_menu->add_tab("?page=carrier&action=$this->search_edit", 'Search');
	}
	
	
	function render(){
		$code = '';
		if(isset($_REQUEST[$this->portal])){
			switch(safe_get($_REQUEST[$this->portal])){
				case $this->load:
					$code .= $this->get_loads();
					break;
			}			
		}else{
			$code = "<title>".App::getSiteName()."-Carriers</title>";
		
		if(logged_in()){
			$code .= "<title>".App::getSiteName()."-Carriers</title>";
			$code .= "<script src='sortable.js'></script>
				<script src='db_save.js.php'></script>
				";
				$code .= $this->portal_script();
				$code .= $this->tab_menu->render();
				$code .= "<div class='tab_sep'></div>";
				$code .= "<div class='content load_content'>";
				switch(get_action()){
					case $this->add_str:
						
						if($this->add()){
							$code .= $this->get_search_results();
						}else{
							$code .= $this->error();
							$code .= $this->feedback;
							$code .= $this->_render_edit();
						}
						break;
					case $this->delete_str:
						$this->delete();
						$code .= $this->get_search_results();
						break;
					case $this->search:
						$code .= $this->get_search_results();	
						break;
					case $this->search_edit:
						$code .= $this->get_search_edit();
						break;
					case $this->edit_str:
						$code .= $this->get_carrier_edit();
						break;
					case $this->new_str:
						$this->create_new();
						header("location: ?page=carrier&action=$this->edit_str&carrier_id=$this->carrier_id&$this->new_str");
						break;
					default:
						$code .= $this->get_search_results();
						break;
				}
				$code .= "</div>";
			}
		}
		return $code;
	}
	function create_new(){
		$this->add();
		$this->carrier_id = $this->last_id;
	}
        
	function get_search_results(){
		$c ='';
		
		$c .= "<center><h2>Carrier Search Results</h2><br>";
		
		
		$sql = "SELECT carrier_id, CONCAT('S', carrier_id) id, name, phys_city, phys_state FROM carrier ";
		$clause = 'WHERE';
		$where='';
                $binds = [];
		if(isset($_REQUEST['carrier_id']) && intval(trim($_REQUEST['carrier_id'], 's S')) > 0){
                    $binds[] = intval(trim($_REQUEST['carrier_id'], 's S'));
                    $where .= " $clause carrier_id = ?";
		}else{
                    if(isset($_REQUEST['name']) && $_REQUEST['name'] != ''){
                         $binds[] = $_REQUEST['name'];
			$where .= " $clause name like ?";
			$clause = 'AND';
                    }
			if(isset($_REQUEST['phys_address']) && $_REQUEST['phys_address'] !=''){
                            $binds[] = $_REQUEST['phys_address'];
                            $where .= " $clause phys_address like ?";
			}
			if(isset($_REQUEST['phys_city']) && $_REQUEST['phys_city'] !=''){
                            $binds[] = $_REQUEST['phys_city'];
                            $where .= " $clause phys_city like ?";
			}
			if(isset($_REQUEST['phys_state']) && $_REQUEST['phys_state'] !=''){
                            $binds[] = $_REQUEST['phys_state'];
                            $where .= " $clause phys_state like ?";
			}
		}
		$sql .= $where;
		$p = new portal($sql);
		$p->hide_column('carrier_id');
		$p->set_primary_key('carrier_id');
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\carrier')\";");
		$c .= $p->render();
		
		return $c;
	}
        
	function get_search_edit(){
		$si = new submit_input($this->search, 'action');
		$f =& $this->get_form();
		$f->set_get();
		$this->omit_all_columns();
		
		$this->unhide_column('carrier_id');
		$this->insert_column('carrier_id');
		$this->insert_column('name');
		$this->insert_column('phys_city');
		$this->insert_column('phys_state');
		$this->set_submit_input($si);
		$c = "<center><h2>Carrier Search</h2>";
		$c .= "Use % as a wildcard character";
		$c .= $this->_render_edit();
		$c .= "</fieldset>";
		return $c;
	}
	
	function get_loads(){
		require_once"includes/portal.php";
		$binds = [$_REQUEST['carrier_id']];
		$p = new portal("SELECT 	l.load_id,
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
							FROM `load` l, load_carrier lc
							WHERE lc.carrier_id = ?
							AND l.load_id = lc.load_id", $binds);
		$p->set_table($this->load);
		$p->hide_column('load_id');
		$p->set_primary_key('load_id');
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\$table')\";");
		return $p->render();
	}
	function get_carrier_menu(){
		$c = "<table><tr>";
		$c .= "<td><a href='?page=carrier'><div class='menu'>All Carriers</div></a></td>";
		$c .= "<td><a href='?page=carrier&action=$this->search_edit'><div class='menu'>Search</div></a></td>";
		$c .= "<td><a href='?page=carrier&action=New'><div class='menu'>New</div></a></td>";
		$c .= $this->back_button();
		$c .= "</tr></table>";
		return $c;
	}
	
	function get_carrier_list(){
		require_once"includes/portal.php";
		
		$p = new portal("	SELECT carrier_id, CONCAT('$this->prefix', carrier_id) id, name, phys_city city, phys_state state
							FROM `carrier`");
		$p->set_table($this->carrier);
		$p->set_row_action("\"row_clicked('\$id', '\$pk', '\carrier')\";");
		$p->set_primary_key('carrier_id');
		$p->hide_column('carrier_id');
		return $p->render();
	}
	function get_carrier_edit(){
		$r = $this->get_row($_REQUEST['carrier_id']);
		$c = "<center><h2>$this->prefix$r[carrier_id]</h2>";
		
		$c .= "<table width='100%'><tr>";
			$c .= "<td valign='top'>";
		//==== left column ====
		//====Carrier Contact====
		$c .= "<fieldset><legend>Contact</legend>";
		$c .= "<table width='100%' border=0>";
		$c .= '<tr><td>Carrier Name</td><td>'.$this->fetch_edit('name', $r['name']).'</td></tr>';
		$c .= '<tr><td>Contact Name</td><td>'.$this->fetch_edit('contact_name', $r['contact_name']).'</td>';
		$c .= '<tr><td>Physical Address</td><td>'.$this->fetch_edit('phys_address', $r['phys_address']).'</td></tr>';
		$c .= '<tr><td>City State Zip</td><td>'.$this->fetch_edit('phys_city', $r['phys_city']).'</td>';
		$c .= '<td>'.$this->fetch_edit('phys_state', $r['phys_state']).'</td>';
		$c .= '<td>'.$this->fetch_edit('phys_zip', $r['phys_zip']).'</td></tr>';
		$c .= '<tr><td>Main Phone</td><td>'.$this->fetch_edit('main_phone_number', $r['main_phone_number']).'</td>';
		$c .= '<tr><td>Fax</td><td>'.$this->fetch_edit('fax', $r['fax']).'</td>';
		$c .= '<tr><td>Carrier Rep</td><td>'.$this->fetch_edit('carrier_rep', $r['carrier_rep']).'</td>';
		$c .= "</table>";
		$c .= "</fieldset>";
		//====end Carrier Contact====
		//====Carrier Accounts Rec====
		$c .= "<fieldset><legend>Accounts Receivable</legend>";
		$c .= "<table width='100%' border=0>";
		$c .= '<tr><td>Address</td><td>'.$this->fetch_edit('acct_rec_address', $r['acct_rec_address']).'</td></tr>';
		$c .= '<tr><td>City State Zip</td><td>'.$this->fetch_edit('acct_rec_city', $r['acct_rec_city']).'</td>';
		$c .= '<td>'.$this->fetch_edit('acct_rec_state', $r['acct_rec_state']).'</td>';
		$c .= '<td>'.$this->fetch_edit('acct_rec_zip', $r['acct_rec_zip']).'</td></tr>';
		$c .= "</table>";
		$c .= "</fieldset>";
		//====end Carrier Accounts Rec====
		//====Carrier Notes====
		$c .= "<fieldset><legend>Notes</legend>";
		$c .= "<table width='100%' border=0>";
		$c .= '<tr><td>Notes</td><td>'.$this->fetch_edit('carrier_notes', $r['carrier_notes']).'</td></tr>';
		$c .= "</table>";
		$c .= "</fieldset>";
		//====End Carrier Notes====
		$c .= "</td><td valign='top'>";
		//==== Carrier Certification ====
		$c .= "<fieldset><legend>Certification</legend>";
		$c .= "<table width='100%' border=0>";
		
		$c .= '<tr><td class="label">Do Not Load</td><td>'.$this->fetch_edit('do_not_load', $r['do_not_load'], !logged_in_as('admin')).'</td>';
		$c .= '<tr><td class="label">Insurance On File</td><td>'.$this->fetch_edit('insurance_on_file', $r['insurance_on_file'], !logged_in_as('admin')).'</td>';
		$c .= '<td><span class="label">Insurance Expires</span></td><td><span class="edit">'.$this->fetch_edit('insurance_expires', $r['insurance_expires'], !logged_in_as('admin')).'</span></td></tr>';
		$c .= '<tr><td class="label">Packet On File</td><td>'.$this->fetch_edit('packet_on_file', $r['packet_on_file'], !logged_in_as('admin')).'</td></tr>';
		$c .= '<tr><td class="label">Certification Holder</td><td>'.$this->fetch_edit('certification_holder', $r['certification_holder'], !logged_in_as('admin')).'</td></tr>';
		$c .= '<tr><td class="label">Limited Liabilty</td><td>'.$this->fetch_edit('limited_liability', $r['limited_liability'], !logged_in_as('admin')).'</td></tr>';
		$c .= '<tr><td class="label">Cargo Limit</td><td>'.$this->fetch_edit('cargo_limit', $r['cargo_limit'], !logged_in_as('admin')).'</td></tr>';
		$c .= '<tr><td class="label">MC Number</td><td>'.$this->fetch_edit('mc_number', $r['mc_number'], !logged_in_as('admin')).'</td></tr>';
		$c .= '<tr><td class="label">ICC Number</td><td>'.$this->fetch_edit('icc_number', $r['icc_number'], !logged_in_as('admin')).'</td></tr>';
		$c .= "</table>";
		$c .= "</fieldset>";
		//==== End Carrier Certification ====
		//====Loads====
		$c .= "<fieldset><legend>Loads</legend>";
		
		$c .= "<div id='$this->load'>
				</div>
				<script>
					get_portal('$this->load');
				</script>";
		$c .= "</fieldset>";
		$c .= $this->style("
		#load .tableContainer{width:97%;height:7em !important;}
		#load table>tbody{ height:3em !important;}");
		//=============
		$c .= "</td></tr></table>";
		if(isset($_REQUEST[$this->new_str])){
			$c .= "<input type='button' onclick='cancel();' value='Cancel'>";
			$c .= "<input type='button' onclick='window.location = \"?page=$this->name\";' value='Save'>";
			$c .= $this->script("
						function cancel(){
							if(confirm('Are you sure you want to cancel?')){
								window.location=\"?action=$this->delete_str&page=$this->name&carrier_id=$_REQUEST[carrier_id]\";
							}
						}");
		}
		return $c;
	}
	
	function fetch_edit($name, $value, $protected=false){
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		if($protected){
			return "<div class='faux_edit'>".$c->get_view_html($value)."</div>";
			
		}else{
			$o = $c->get_edit_html($value);
			$o->set_id("action=update&table=$_REQUEST[page]&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
			$o->add_attribute('onchange', 'db_save(this);');
			$script = "<script>
		var i = document.getElementById('$name');
		i.setReturnFunction(onchange);
		</script>";
			return $o->render();
		}
	}
	
	function portal_script(){
		return "
			<script>
			function get_portal(table){
				var d = document.getElementById(table);
				d.innerHTML = 'Loading '+table;
				var portal = getFromURL('?page=$this->name&portal='+table+'&carrier_id=".safe_get($_REQUEST['carrier_id'])."&action=portal&".SMALL_VIEW."');
				d.innerHTML = '';
				d.innerHTML = portal;
			}
			</script>";
			
	}
}
?>