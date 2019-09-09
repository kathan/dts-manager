var req;

function db_save(obj) {
    var params = obj.id;
    if (obj.type == 'checkbox') {
        var value = obj.checked;
    } else {
        var value = obj.value;
    }
    var req = false;
    // branch for native XMLHttpRequest object
    if (window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        } catch (e) {
            req = false;
        }
        // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        try {
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                req = false;
            }
        }
    }
    var query_str = params + escape(value);
    if (req) {
        var url = "db_save.php";
        req.open("POST", url, false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=ISO-8859-13");
        req.setRequestHeader("Content-length", query_str.length);
        req.setRequestHeader("Connection", "close");
        req.send(query_str);
        if (req.status === 200) {
            if (req.responseText != 1) {
                console.log(req.responseText);
            }
        } else {
            alert("There was a problem retrieving the XML data:\n" + req.statusText);
        }

    } else {
        alert("Could not create request. Save failed.");
    }

}

function processReqChange() {
    // only if req shows "loaded"
    if (req.readyState == 4) {
        // only if "OK"
        if (req.status == 200) {
            req.responseText
            if (req.responseText != 1) {
                console.log(req.responseText);
            }
        } else {
            alert("There was a problem retrieving the XML data:\n" +
                req.statusText);
        }
    }
}