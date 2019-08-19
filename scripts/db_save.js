function db_save2(obj, values)
{
	if(obj.type == 'checkbox')
	{
		var value = obj.checked;
	}else{
		var value = obj.value;
	}
	values[obj.name] = value;
	$.post('db_save.php', values, function(e){
		$(obj).trigger('data_saved');
	});
}