function addItem(item_id)
{
	document.getElementById("inAddItem").value=item_id;
	document.getElementById("frmAddItem").submit();
}

function addItemAjax(item_id)
{
	var httpRequest;
	//document.getElementById("ajaxButton").addEventListener('click', makeRequest);
	httpRequest = new XMLHttpRequest();

	if (!httpRequest)
	{
		alert('Giving up: ( Cannot create an XMLHTTP instance');
		return false;
	}

	httpRequest.onreadystatechange = doneAddingItem;
	//httpRequest.open('GET', 'ajax.php', true);
	httpRequest.open('POST', 'ajax.php', true);
	httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	httpRequest.send('action=addItem&listUuid=' + encodeURIComponent(LIST_UUID) +'&itemId=' + encodeURIComponent(item_id));

	function doneAddingItem()
	{
		if (httpRequest.readyState === XMLHttpRequest.DONE)
		{
			if (httpRequest.status === 200)
			{
//				alert(httpRequest.responseText);
				replaceActiveList(httpRequest.responseText);
			}
			else
			{
				alert('There was a problem with the request.');
			}
		}
	}
}

function replaceActiveList(json_list)
{
	var templ = document.getElementById('listTemplate').innerHTML;
	var js_list = JSON.parse(json_list);
	var repl_html = '';
	for (let i in js_list.items)
	{
		repl_html += templ.replace('${name}', js_list.items[i].name);
	}
	document.getElementById('theCurrentList').innerHTML = repl_html;
}
