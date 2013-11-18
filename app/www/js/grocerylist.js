$(document).ready(function() {    

var parent = document.getElementById('grocerylist');
var listItem = document.createElement('li');
listItem.setAttribute('id','groceryItem');
listItem.innerHTML = "<a href='#'>FIRST ITEM</a>";
parent.appendChild(listItem);

var list = document.getElementById('grocerylist');
$(list).grocerylist("refresh");

});