<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/style.css" type="text/css" media="all">
        <script src="js/events.js" type="text/javascript"></script>
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/base.js" type="text/javascript"></script>
        <title>DTS-Loads</title>
    </head>
    <body>
        <script type="text/javascript">
            function get_portal(table, params){
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
					
            function get_module(name, params){
                var d = document.getElementById(name+'_module');
                d.innerHTML = 'Loading '+name;
                try{
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
                    width = 600;
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
        </script>
    <div class="content load_content" id="content">
        <h2>Customer Search</h2>Use % as a wildcard character
        <form method="GET" action="index.php" name="" enctype="multipart/form-data">
            <input type="hidden" name="page" value="load">
            <input type="hidden" name="action" value="customer_search_result">
            <input type="hidden" name="load_id" value="1061611">
            <table class="edit_class">
                <tr>
                    <td class="label_class">Customer Id:</td>
                    <td class="edit_class">
                        <input type="text" name="customer_id" id=""  size="11" maxlength="11" value="" />
                    </td>
                </tr>
                <tr>
                    <td class="label_class">Name:</td>
                    <td class="edit_class">
                        <input type="text" name="name" id=""  maxlength="30" value="">
                    </td>
                </tr>
                <tr>
                    <td class="label_class">City:</td>
                    <td class="edit_class">
                        <input type="text" name="city" id=""  maxlength="100" value="">
                    </td>
                </tr>
                <tr>
                    <td class="label_class">State:</td>
                    <td class="edit_class">
                        <input type="text" name="state" id=""  size="2" maxlength="2" value="">
                    </td>
                </tr>
                <tr>
                    <td class="label_class">
                    </td>
                    <td class="edit_class">
                        <input type="submit" name="" id=""  value="Search">
                    </td>
                </tr>
            </form>
        </table>
        <input type="button" onclick="window.close();" value="Close"></div>
    </body>
</html>