<?php
require_once "includes/dts_table.php";
class customer_notes_table extends dts_table
{
	function customer_notes_table()
	{
		$this->dts_table('customer_notes');
		$this->hide_delete();
		$this->hide_column('note_id');
		$this->hide_column('last_updated');
		$this->set_label('customer_id', 'Customer');
		$this->add_table_params('page', 'customer_notes');
		
		require_once"includes/hidden_input.php";
		$i = new hidden_input('page', 'customer_notes');
		$this->add_other_inputs($i);
		
		$c =& $this->get_column('notes');
		$c->set_rows(10);
		$c->set_cols(80);
		
		$c =& $this->get_column('customer_id');
		$c->set_parent_label_column('name');
	}
	
	function render()
	{
		$code = "<title>".SITE_NAME."-Customer Notes</title>";
		
		if(logged_in())
		{
			switch(get_action())
			{
				case $this->portal:
					$code .= $this->customer_note_portal();
					break;
				case $this->add_str:
					$code = "<script>
					function refresh_close()
					{
						window.opener.get_portal('customer_notes');
						window.close();
					}
							window.onload = refresh_close;
						</script>";
					$this->add();
				case $this->new_str:
					$code .= '<center><h2>New Note</h2>';
					$code .= $this->new_note();
					break;
				case $this->edit_str:
					$code .= '<center><h2>Edit Note</h2>';
					$code .= $this->_render_edit();
					break;
				default:
					$code .= '<center><h2>Note List</h2>';
					$code .= $this->_render_list();
					break;
			}
		}
		return $code;
	}
	
	function customer_note_portal()
	{
		require_once("includes/portal.php");
		
		$p = new portal("SELECT note_id, notes
							FROM customer_notes
							WHERE customer_id = $_REQUEST[customer_id]");
		$p->set_table('customer_notes');
		$p->set_primary_key('note_id');
		return $p->render();
	}
	
	function new_note()
	{
		$c ='<script>
				function submit_close()
				{
					var f = document.getElementById("new_note");
					f.submit();
				}
				</script>';
		$c .= '<table><tr>';
		$c .= "<form id='new_note' onsubmit=submit_close'' method='post'>";
		$c .= "<input type='hidden' name='page' value='customer_notes'>";
		$c .= "<input type='hidden' name='action' value='Add'>";
		$cust = $this->get_column('customer_id');
		$cust_input = $cust->get_edit_html($_REQUEST['customer_id']);
		$c .= '<tr><td>Customer</td><td>'.$cust_input->render().'</td></tr>';
		$c .= '<tr><td>Notes</td><td>'.$this->fetch_edit('notes').'</td></tr>';
		$c .= '<tr><td><input type="button" onclick="submit_close()" value="Save"></td></tr>';
		$c .= "</form>";
		$c .= '</tr></table>';
		return $c;
	}
	function fetch_edit($name)
	{
		$c =& $this->get_column($name);
		$pk_obj =& $this->get_primary_key();
		$pk_name = $pk_obj->get_name();
		
		$o = $c->get_edit_html();
		if(isset($_REQUEST[$pk_name]))
		{
			$o->set_id("$this->action=$this->update&table=$_REQUEST[page]&".$pk_name."=".$_REQUEST[$pk_name]."&".$name."=");
		}
		return $o->render();
	}
}
?>