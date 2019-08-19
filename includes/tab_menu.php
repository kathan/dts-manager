<?php
require_once"tab_item.php";
require_once"menu.php";
class tab_menu extends menu
{
	
	function __construct()
	{
		$this->active_class='active_tab';
		parent::__construct();
	}
	function add_tab($link, $label)
	{
		$new_tab = new tab_item($link, $label);
		$this->add_item($new_tab);
	}
	
	function render()
	{
		//echo $this->active;
		if(array_key_exists($this->active, $this->items))
		{
			$active_item =& $this->items[$this->active];
			$active_item->class = $this->active_class;
		}
		
		$c = "
			<div class='tab'>
  				<ul>\n";
  		$links = array_keys($this->items);
  		foreach($links as $link)
  		{
  			//echo $link." gooch<br>";
  			//if(array_key_exists($link, $this->items))
  			//{
	  			$c .= $this->items[$link]->render();
			//}
		}
		$c .= "	</ul>
			</div><br><br>\n";
		return $c;
	}
	
	
}
?>