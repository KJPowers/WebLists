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
//				alert(httpRequest.responseText);	// TODO: stop doing this when we're done debugging
				replaceAvailableItems(httpRequest.responseText);
				replaceActiveList(httpRequest.responseText);
				document.getElementById('itemSearch').value = '';
			}
			else
			{
				alert('There was a problem with the request.');
			}
		}
	}
}

function toggleMarkedAjax(item_id)
{
	var httpRequest;
	//document.getElementById("ajaxButton").addEventListener('click', makeRequest);
	httpRequest = new XMLHttpRequest();

	if (!httpRequest)
	{
		alert('Giving up: ( Cannot create an XMLHTTP instance');
		return false;
	}

	httpRequest.onreadystatechange = doneTogglingMarked;
	//httpRequest.open('GET', 'ajax.php', true);
	httpRequest.open('POST', 'ajax.php', true);
	httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	httpRequest.send('action=toggleMarked&listUuid=' + encodeURIComponent(LIST_UUID) +'&itemId=' + encodeURIComponent(item_id));

	function doneTogglingMarked()
	{
		if (httpRequest.readyState === XMLHttpRequest.DONE)
		{
			if (httpRequest.status === 200)
			{
//				alert(httpRequest.responseText);  // TODO: stop doing this when we're done debugging
//				replaceAvailableItems(httpRequest.responseText);
				replaceActiveList(httpRequest.responseText);
			}
			else
			{
				alert('There was a problem with the request.');
			}
		}
	}
}

function clearMarkedAjax()
{
	var httpRequest;
	//document.getElementById("ajaxButton").addEventListener('click', makeRequest);
	httpRequest = new XMLHttpRequest();

	if (!httpRequest)
	{
		alert('Giving up: ( Cannot create an XMLHTTP instance');
		return false;
	}

	httpRequest.onreadystatechange = doneClearingMarked;
	//httpRequest.open('GET', 'ajax.php', true);
	httpRequest.open('POST', 'ajax.php', true);
	httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	httpRequest.send('action=clearMarked&listUuid=' + encodeURIComponent(LIST_UUID));

	function doneClearingMarked()
	{
		if (httpRequest.readyState === XMLHttpRequest.DONE)
		{
			if (httpRequest.status === 200)
			{
//				alert(httpRequest.responseText);  // TODO: stop doing this when we're done debugging
				replaceAvailableItems(httpRequest.responseText);
				replaceActiveList(httpRequest.responseText);
			}
			else
			{
				alert('There was a problem with the request.');
			}
		}
	}
}

function newItemAjax()
{
	var httpRequest;
	//document.getElementById("ajaxButton").addEventListener('click', makeRequest);
	httpRequest = new XMLHttpRequest();

	if (!httpRequest)
	{
		alert('Giving up: ( Cannot create an XMLHTTP instance');
		return false;
	}

	httpRequest.onreadystatechange = doneAddingNewItem;
	//httpRequest.open('GET', 'ajax.php', true);
	httpRequest.open('POST', 'ajax.php', true);
	httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	httpRequest.send('action=newItem&listUuid=' + encodeURIComponent(LIST_UUID) + '&itemName=' + encodeURIComponent(document.getElementById('itemSearch').value));

	function doneAddingNewItem()
	{
		if (httpRequest.readyState === XMLHttpRequest.DONE)
		{
			if (httpRequest.status === 200)
			{
//				alert(httpRequest.responseText);  // TODO: stop doing this when we're done debugging
				replaceAvailableItems(httpRequest.responseText);
				replaceActiveList(httpRequest.responseText);
				document.getElementById('itemSearch').value = '';
			}
			else
			{
				alert('There was a problem with the request.');
			}
		}
	}
}

function updateSortAjax(oldIdx, newIdx)
{
	var httpRequest;
	//document.getElementById("ajaxButton").addEventListener('click', makeRequest);
	httpRequest = new XMLHttpRequest();

	if (!httpRequest)
	{
		alert('Giving up: ( Cannot create an XMLHTTP instance');
		return false;
	}

	httpRequest.onreadystatechange = doneUpdatingSort;
	//httpRequest.open('GET', 'ajax.php', true);
	httpRequest.open('POST', 'ajax.php', true);
	httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	httpRequest.send('action=updateSort&listUuid=' + encodeURIComponent(LIST_UUID) +
	                   '&oldIdx=' + encodeURIComponent(oldIdx) +
	                   '&newIdx=' + encodeURIComponent(newIdx));

	function doneUpdatingSort()
	{
		if (httpRequest.readyState === XMLHttpRequest.DONE)
		{
			if (httpRequest.status === 200)
			{
//				alert(httpRequest.responseText);  // TODO: stop doing this when we're done debugging
				replaceActiveList(httpRequest.responseText);
			}
			else
			{
				alert('There was a problem with the request.');
			}
		}
	}
}

function refreshAjax()
{
	var httpRequest;
	//document.getElementById("ajaxButton").addEventListener('click', makeRequest);
	httpRequest = new XMLHttpRequest();

	if (!httpRequest)
	{
		alert('Giving up: ( Cannot create an XMLHTTP instance');
		return false;
	}

	httpRequest.onreadystatechange = doneRefreshing;
	//httpRequest.open('GET', 'ajax.php', true);
	httpRequest.open('POST', 'ajax.php', true);
	httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	httpRequest.send('action=refresh&listUuid=' + encodeURIComponent(LIST_UUID));

	function doneRefreshing()
	{
		if (httpRequest.readyState === XMLHttpRequest.DONE)
		{
			if (httpRequest.status === 200)
			{
//				alert(httpRequest.responseText);  // TODO: stop doing this when we're done debugging
				replaceAvailableItems(httpRequest.responseText);
				replaceActiveList(httpRequest.responseText);
			}
			else
			{
				alert('There was a problem with the request.');
			}
		}
	}
}

function visibilityChanged()
{
	if (document.visibilityState === 'visible')
	{
		//refreshAjax();
		periodicallyRefresh();
	}
}

function periodicallyRefresh()
{
	if (document.visibilityState === 'visible')
	{
		refreshAjax();
		setTimeout(periodicallyRefresh, 20000);
	}
}

function replaceActiveList(json_list)
{
	var templ = document.getElementById('listItemTemplate').innerHTML;
	var js_list = JSON.parse(json_list);
	var repl_html = '';
	for (let i in js_list.listItems)
	{
		repl_html +=
		  templ
		    .replace('${name}',   js_list.listItems[i].name)
		    .replace('${id}',     js_list.listItems[i].id)
		    .replace('${class}',  js_list.listItems[i].class);
	}
	document.getElementById('theCurrentList').innerHTML = repl_html;
	document.getElementById('theCurrentList').className   = (repl_html === '' ? 'd-none'  : 'd-block');
	document.getElementById('currentListEmpty').className = (repl_html === '' ? 'd-block' : 'd-none');
}

function replaceAvailableItems(json_list)
{
	var templ = document.getElementById('nbItemTemplate').innerHTML;
	var js_list = JSON.parse(json_list);
	var repl_html = '';

	for (let i in js_list.nbItems)
	{
		repl_html +=
		  templ
		    .replace('${name}',  js_list.nbItems[i].name)
		    .replace('${id}',    js_list.nbItems[i].id)
		    .replace('${class}', js_list.nbItems[i].class);
	}
	document.getElementById('availableItems').innerHTML = repl_html;

	itemSearchFilter();
}

function itemSearchKeyPress(event)
{
	if (event.key === "Enter")
	{
		event.preventDefault();
		document.getElementById("nb_newItemButton").click();
	}
}

function itemSearchFilter()
{
	var searchText, lis, i, a, txtValue;
	searchText = document.getElementById("itemSearch").value.toLowerCase();
	lis = document.getElementById("availableItems").getElementsByTagName("li");
	for (i=0; i<lis.length; i++)
	{
		a = lis[i].getElementsByTagName("a")[0];
		txtValue = a.textContent || a.innerText;
		if (txtValue.toLowerCase().indexOf(searchText) > -1)
		{
			lis[i].classList.remove("d-none");
		}
		else
		{
			lis[i].classList.add("d-none");
		}
	}
}

function registerFocusGrab(element)
{
	new IntersectionObserver((entries, observer) => { entries.forEach(entry => { if (entry.intersectionRatio > 0) { entry.target.focus(); } } ); },
	                         { root: document.documentElement } )
	  .observe(element);
}

