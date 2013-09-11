function setDate()
{
var date = new Date();
var hours = date.getHours();
var month = date.getMonth() + 1;
document.getElementById('commentDate').value = date.getFullYear() + '-' + month + '-' + date.getDate() + ' ' + hours + ':' + date.getMinutes() + ':' + date.getSeconds();
}

//function to update the state list
function addSchoolName(name)
{
	alert(name);
}