function makeButton(obj, name)
{
	obj.src = "../images/"+name;
}
function submitForm(userid)
{
	var id = 'id=' + userid + '&';
	var comment = 'comment=' +document.Comments.comment.value + '&time=' + document.Comments.time.value;
	var message = 'commentsBox';
	getData('getComments.php', id + comment, message);
	document.Comments.comment.value = "";
	return false;
}