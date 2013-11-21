function changePage(url, data){
  if(data == null)
    data = {transition: "slide" };

  $.mobile.changePage(url, data);
}

/* convert item status code to text */
function itemStatusToText(status_code){
  switch(status_code){
    case 0: 
      return "rejected";
    case 1:
      return "pending";
    case 2:
      return "accepted";
    default:
      return "unknown";
  }
}
