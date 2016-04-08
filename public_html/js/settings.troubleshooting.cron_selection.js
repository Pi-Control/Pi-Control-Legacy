function selecttxt(objId)
{
	if (document.selection)
	{
		var range = document.body.createTextRange();
		range.moveToElementText(document.getElementById(objId));
		range.select();
	}
	else if (window.getSelection)
	{
		var range = document.createRange();
		range.selectNode(document.getElementById(objId));
		window.getSelection().addRange(range);
	}
}