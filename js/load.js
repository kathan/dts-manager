$(function(){
    $('#od_load_mod_close').click(function(){
        hideOverdueLoadModule();
    });
    window.setInterval(function(){
        getOverdueLoads();
        getApproachingLoads();
    }, 900000);//15 minutes
});
	
function getOverdueLoads(){
    $.getJSON('overdue_loads.php',function(data){
        document.getElementById('od_load_mod').overdue_count = data.length;
        if(data.length > 0){
            showOverdueLoad(data);
        }else
        {
            $('#od_load_mod_msg').html('');
        }
    });
}
	
function getApproachingLoads(){
    $.getJSON('approaching_loads.php',function(data){
        $('#od_load_mod').approach_count = data.length;
        if(data.length > 0){
            showApproachingLoad(data);
        }else{
            $('#od_approach_mod_msg').html('');
            if(document.getElementById('od_load_mod').overdue_count == 0){
                hideOverdueLoadModule();
            }
				
        }
    });
}
	
function showApproachingLoad(data){
    $('#od_load_mod').css('display', 'block');
    var e;
    data.length == 1 ? e = 'event' : e = 'events';
    $('#od_approach_mod_msg').html('<a target="dts_od" href="?page=overdue">You have '+data.length+' approaching '+e+'.</a>');
}
	
function showOverdueLoad(data){
    $('#od_load_mod').css('display', 'block');
    var e;
    data.length == 1 ? e = 'event' : e = 'events';
    $('#od_load_mod_msg').html('<a target="dts_od" href="?page=overdue">You have '+data.length+' overdue '+e+'.</a>');
}

function hideOverdueLoadModule(){
    $('#od_load_mod').css('display', 'none');
}

function locateOverdudLoadModule(){
    var llm = $('#od_load_mod');
    llm.css('top', $(window).height() / 2);
}

function setDays(){
    var days_of_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var days_of_month_LY = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var year = document.getElementById('year');
    var month = document.getElementById('month');
    var day = document.getElementById('day');
    var cur_date = new Date()
    cur_date.setMonth(month.value);
    cur_date.setFullYear(year.value);
    if (leapYear(year.value)){
        days_of_month = days_of_month_LY;
    }
												
    var d_o_m = days_of_month[month.value-1];
							
    //==== revove all options ====
    var selected = day.selectedIndex+1;
    while(day.options.length > 0){
        day.remove(0);
    }
							
    for(var i=1; i <= d_o_m;i++){
        var new_opt = document.createElement('option');
        new_opt.value = i;
        new_opt.text = i;
        if(i == selected){
            new_opt.selected = true;
        }
        try
        {
            day.add(new_opt,null); // standards compliant
        }catch(ex){
            day.add(new_opt); // IE only
        }
    }
							
}
function leapYear(year){
    if ((year/4)   != Math.floor(year/4))   return false;
    if ((year/100) != Math.floor(year/100)) return true;
    if ((year/400) != Math.floor(year/400)) return false;
    return true;
}

function getPortal(table, params){
    try{
        var d = document.getElementById(table+'_portal');
        d.innerHTML = 'Loading '+table;			
        var url = '?page=load&portal='+table+'&action=portal&sml_view&'+params;
        var portal = getFromURL(url);
    }catch(e){
        alert('Error in get_portal:'+e.description + ' url:' + url);
    }
    d.innerHTML = '';
    d.innerHTML = portal;
				
}
					
function getModule(name, params){
    var d = document.getElementById(name+'_module');
    d.innerHTML = 'Loading '+name;
    try
        {
        var url = '?page=load&module='+name+'&sml_view&'+params;
        var module = getFromURL(url);
    }catch(e){
        alert('Error in module_script:'+e.description + ' url:' + url);
    }
    d.innerHTML = '';
    d.innerHTML = module;
				
}
								
function popUp(URL, id, width, height){
    if(!width){
        width=600;
    }
    if(!height){
        height = 600;
    }
    if(!id){
        day = new Date();
        id = day.getTime();
    }
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,location=0,statusbar=0,menubar=0,resizable=1,width="+width+",height="+height+",left = 0,top = 0');");
}

function makeNew(){
    var p = document.getElementById("page");
					
    if(p.value){
        p.form.submit();
    }
}