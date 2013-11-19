server = {
  address: "http://www.andrew.cmu.edu/user/kku/room703.json",

  /* getJSON: convert reponse into a json object */
  getJSON: function(s){
    try{
      var jobj = jQuery.parseJSON(s);
      return jobj;
    }catch(err){
      return null;
    }
  },

  /* retrieve address of server */
  getAddress: function(){
    if(window.localStorage["server_address"] != undefined)
      return;

    var url = server.address;

    var success = function(res){
      if(res == null){
        console.log("retrieve server address failed");
        window.localStorage["server_address"] = undefined;
      }
      else{
        addr = "http://" + res.server_address.replace("http://", "") + "/client_interface";
        console.log("retrieved server address: " + addr);
        window.localStorage["server_address"] = addr;
      }
    };

    $.ajax({
      type: "GET",
      url: url,
      async: false,
      jsonpCallback: "setServerAddress",
      contentType: "application/json",
      dataType: "jsonp",
      success: success,
      error: function(e){ console.log(e.message); }
    });
  },

  request: function(url, data, jsonCallbackName, successCallback, errorCallback){
    server.getAddress();

    if(window.localStorage["server_address"] == undefined)
      return;

    if(url.substring(0, 1).localeCompare("/") != 0)
      url = "/" + url;

    $.ajax({
      type: "GET",
      url: window.localStorage["server_address"] + url,
      data: data,
      async: false,
      jsonpCallback: jsonCallbackName,
      contentType: "application/json",
      dataType: "jsonp",
      success: successCallback,
      error: errorCallback
    });
  }
};
