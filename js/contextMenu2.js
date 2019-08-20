function FolderContextMenu(obj)
{
	var o = $(obj);
	o.css('list-style', 'none');
	o.css('padding-left', '0px');
	o.css('padding-top', '3px');
	o.css('padding-bottom', '3px');
	o.css('border', '1px solid black');
	o.css('background-color', 'white');
	o.css('position', 'absolute');
	o.move = function(position)
	{
		o.offset(position);
	};
	
	o.clearRows = function()
	{
		o.html('');
	};

	o.show = function()
	{
		this.css('visibility', 'visible');
	};

	o.hide = function()
	{
		this.css('visibility', 'hidden');
	};
	
	o.selected = function(selObj)
	{
		
		this.hide();
		return selObj;
	};
	
	o.addRow = function(name, onclick)
	{
		var li = new FolderContextMenuItem(this, name, onclick);
		return li;
	};

	o.hide();
	var data = {menu: o};
	$(document).click(data, function(e)
		{
			//$('#feedback').html(propertiesToString(data));
			data.menu.hide();
		});
	o.addRow('&nbsp;', function()
			{
				this.parent.hide();
				return false;
			});
	return o;
}

function FolderContextMenuItem(parent, name, onclick)
{
	var obj = document.createElement('li');
	parent.append(obj);
	
	obj.parent = parent;
	obj.listName = name;
	var o = $(obj);
	o.css('padding', '1px');
	o.css('cursor', 'pointer');
	
	o.mouseover(function(){
		$(this).css('background-color','#3875d7');
		$(this).css('color','white');
	});
	
	o.mouseout(function(){
		$(this).css('background-color','transparent');
		$(this).css('color','black');
	});
	
	o.html(name);
	onclick ? o.click(onclick) : '';
	//o.click(function(){alert('boo')})
	return o;
}