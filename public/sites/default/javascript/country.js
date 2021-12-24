
function findIndexPage(){
    $.getJSON('https://json.geoiplookup.io', function(data) {
	  console.log (data.country_name);
	  var ajaxPromise = fetch('/content/countries.json')
		.then(function(response) {
			return response.json();
		})
		.then(function(jsonFile) {
			let index = 'index';
			jsonFile.forEach(function (element){
				if (element.code === data.country_code){
					index = element.index;
				}
			});
			localStorage.setItem( 'myFriendsIndex', index); // store index
			return index;
		})
		.then(function(index) {
			showIndexPage(index);
		})
	});
}

function showIndexPage(index){
	var ajaxPromise = fetch('/content/' + index + '.json')
	.then(function(response) {
		return response.json();
	})
	.then(function(jsonFile) {
		makeUnorderedList(jsonFile);
	});
}

function makeUnorderedList(jsonFile){
	let container = document.getElementById("mycontent");
	var myList = document.createElement("ul");
	myList.className="list";
	jsonFile.forEach (function (element){
		var listItem = document.createElement("li");
		listItem.className = "table-view-cell";
		var aLink = document.createElement('a');
		aLink.href =  '/content/' + element.folder; // Insted of calling setAttribute 
		aLink.innerHTML = element.name // <a>INNER_TEXT</a>
	  // append the value in the list item
	  listItem.appendChild(aLink); 
	  // append the list item in the list
	  myList.appendChild(listItem);
	});
	// append the list in the container
	container.appendChild(myList);
}
