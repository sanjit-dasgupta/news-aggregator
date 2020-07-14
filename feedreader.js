var intervalID = setInterval(function(){
    fetchFile();
}, 30000*2);
var alternateTitle = null;
document.addEventListener('visibilitychange', handleVisibilityChange, false);

function fetchFile(updateFlag = true){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		    clearInterval(intervalID);
		    intervalID = setInterval(function(){
                fetchFile();
            }, 30000*2);
			//if(!updateFlag)
			document.getElementById("mainContent").innerHTML = "";
			updateData(this.responseXML, document.getElementById("lastModified").value);
			document.getElementById("lastModified").value = xhttp.getResponseHeader("Last-Modified");
		}else{
		    clearInterval(alternateTitle);
		}
    };
	xhttp.open("GET", "news.xml", true);
	if(updateFlag) xhttp.setRequestHeader("If-Modified-Since", document.getElementById("lastModified").value);
	xhttp.send();
}
function updateData(xmlObject, lastModified){
	var items = xmlObject.getElementsByTagName("item"), headline, link, image, description, category, timestamp, Node, referenceNode;
	var flag = false;
	lastModified = Math.floor(Date.parse(lastModified)/2000);
	referenceNode = document.getElementById("mainContent").firstChild;
	for (var i = 0; i < items.length; i++){
		headline = items[i].getElementsByTagName("title")[0].childNodes[0].nodeValue;
		link = items[i].getElementsByTagName("link")[0].childNodes[0].nodeValue;
		image = items[i].getElementsByTagName("image");
		if(image.length > 0){
			image = image[0].getElementsByTagName("url")[0].childNodes[0].nodeValue;
		}else{
			image = "https://cdn3.iconfinder.com/data/icons/news-3/500/news-report-media_7-512.png";
		}
		description = items[i].getElementsByTagName("description")[0].childNodes[0].nodeValue;
		category = items[i].getElementsByTagName("category")[0].childNodes[0].nodeValue;
		timestamp = Number(items[i].getElementsByTagName("pubDate")[0].childNodes[0].nodeValue);
		if(timestamp < lastModified) break;
		if(!isCat(category)) continue;
		if(description.length > 200) description = description.substring(0, 200) + "...";
		//if(headline.length > 10) headline = headline.substring(0, 10) + "...";
		Node = addHtmlNode(headline, link, image, description, category, timestamp);
		document.getElementById("mainContent").insertBefore(Node, referenceNode);
		flag = true;
		//else document.getElementById("mainContent").insertBefore(Node, document.getElementById("mainContent").firstChild);
	}
	if(flag === true && document.visibilityState == "hidden"){
	    alternateTitle = setInterval(function(){
            if(document.title === "Latest News") document.title = "News posts updated !";
            else document.title = "Latest News";
        }, 1);
	}
}
function isCat(category){
	var items = document.getElementsByName('categories');
	for(var i=0; i<items.length; i++){
		if(items[i].type=='checkbox' && items[i].checked===true && items[i].value === category) return true;
	}
	return false;
}
function newNode(classVal, type = "div"){
	var root = document.createElement(type);
	var att = document.createAttribute("class");
	att.value = classVal;
	root.setAttributeNode(att);
	return root;
}
function addHtmlNode(headline, link, image, description, category, timestamp, prevNode){
	var column = newNode("col-xs-6 col-lg-4 my-2");
	var root = newNode("card h-100 text-center");
	var img = newNode("card-img-top img-fluid", "img");
	var att = document.createAttribute("src");
	att.value = image;
	img.setAttributeNode(att);
	root.appendChild(img);
	var div = newNode("card-body");
	div.innerHTML = "<h5 class=\"card-title\">" + headline + "</h5>";
    div.innerHTML += "<p class=\"card-text\">" + description + "</p>";
    var divLink = newNode("card-footer");
	divLink.innerHTML = "<a href=\"" + link + "\" class=\"btn btn-primary\" target=\"_blank\">View Full Article</a>";
    var divDate = newNode("card-footer");
	divDate.innerHTML = "<small class=\"text-muted\">Last updated" + formatSeconds(Math.ceil(Date.now()/1000) - timestamp) + " ago</small>";
	root.appendChild(div);
	root.appendChild(divLink);
	root.appendChild(divDate);
	column.appendChild(root);
	return column;
}
function handleVisibilityChange() {
  if (document.visibilityState === "visible") {
      clearInterval(alternateTitle);
      document.title = "Latest News";
  }
}
function formatSeconds(seconds){
	var days, hours, minutes, ans = "";
	days = Math.floor(seconds/86400);
	seconds -= days*86400;
	hours = Math.floor(seconds/3600);
	seconds -= hours*3600;
	minutes = Math.floor(seconds/60);
	if(days > 0){
		ans = ans + " " + days;
		ans += (days==1?" day":" days");
	}
	if(hours > 0){
		ans = ans + " " + hours;
		ans += (hours==1?" hr":" hrs");
	}
	if(minutes > 0){
		ans = ans + " " + minutes;
		ans += (minutes==1?" min":" mins");
	}
	return ans;
}