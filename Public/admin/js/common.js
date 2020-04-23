var base_url = "http://"+window.location.host+"/";
var ajax_url = base_url + "api.php?service=";
var url = "/api.php?service=";


function ajax(url, type, callback, data){
    if(!data){
        data={}
    }
    $.ajax({
        "url": ajax_url+url,
        "cache": false,
        "data":data,
        "type": type
    }).success(function (data, status, header) {
        //success body
        console.log(data)
        callback(data)
    }).fail(function (header, status, errorThrown) {
        //fail body
        console.log(header)
    })
}

