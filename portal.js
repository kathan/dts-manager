//requires base.js
	
function get_portal(table)
{
	var d = document.getElementById(table);
	d.innerHTML = 'Loading '+table;
	//alert(document.URL);
	var portal = getFromURL('http://darrelkathan.com/dts/portal.php?table='+table);
	d.innerHTML = portal;
}