/*
 * This Section is responsible for creatting 
 * Ajax Object.
 */

function createRequestObject() {
    var ro;     
    ro = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();

    return ro;
}

var http = createRequestObject();

/**
 * GPS Triangulation with Google API
 * 
 */
function success(position) {
  var mapcanvas = document.createElement('div');
  mapcanvas.id = 'mapcontainer';
  mapcanvas.style.height = '400px';
  mapcanvas.style.width = '600px';
  document.querySelector('article').appendChild(mapcanvas);
  var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
  //var coords = '(-26.0381,28.01845000000003)';
  //alert('Response: '+coords->jb);
  
  
  //var oCoords = objToString(coords);
  //alert(coords);
  
 // console.log(coords);
   
//var obj = JSON.parse(coords);
//alert(obj);

//var keys = Object.keys(coords);
//alert(keys);

//var_dump ();
////alert('OLD: ' + coords + '    NEW: '+coords.kb);
//myJSONObject.bindings[0].method


var geoLat = coords.jb
var geoLong = coords.kb
  
  
  //print_r(coords);
  
  //alert(typeof(coords));
  //dump(v, howDisplay, recursionLevel);
  //dump(coords, 'alert', 0);

  //var action = "lat-long";
  //var phpVars = coords.replace(/,/g, "|");
  //phpVars.substring(0, phpVars.length - 1);
  /*
  var re = /($/;
  phpVars.replace(re, "");
  var re = /)$/;
  phpVars.replace(re, "");
  */

  //var re = /)/gi;
 // var str = "Apples are round, and apples are juicy.";
  //var newstr = coords.replace(re, "|");

  
 // alert(newstr);
 
 	var action = "lat-long";
  	var array = [action,geoLat,geoLong];
  	sndReq(array);

  var options = {
    zoom: 15,
    center: coords,
    mapTypeControl: false,
    navigationControlOptions: {
    	style: google.maps.NavigationControlStyle.SMALL
    },
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("mapcontainer"), options);
  var marker = new google.maps.Marker({
      position: coords,
      map: map,
      title:"You are here!"
  });
}

if (navigator.geolocation) {
	//alert('Geo Location is Supported!!!!!!!!!!!!');
  navigator.geolocation.getCurrentPosition(success);
} else {
	//error('Geo Location is not supported');
  alert('Geo Location is not supported');
}

/*
function sndReq(array) {
	//var progress = '<img width=30 height=30 src="32-0.gif">';
	//showhide('ProgressIndicator',1); 
	//document.getElementById('ProgressIndicator').innerHTML = progress;
    
	var d = new Date();
    		
    http.open('get', 'Process.php?array='+array+"&neverused="+d.getTime());
    http.onreadystatechange = handleResponse;
    http.send(null);
}

function handleResponse() {
     if(http.readyState == 4){
          var response = http.responseText;
          alert(response);     
    }
}

*/

/**
 * This section is better structured... OOP
 */


/**
 * This function requests Page Content based on the action param and rowID received...
 * @param action...
 */
function requestContent(category, action, rowID)
{
	//alert('FIRST: ' + category + ' ' + action + ' ' + rowID);
	
	content = new setContent(category, action, rowID);
	
	var array = content.sendArray();
	
	//alert('Array to send: '+ array);
	
	if(array != false)
	{
		//alert('Sending : ' +array);
		content.sendRequest(array);
	}	
}

/**
 * This function requests Page Content [Promotion Related] based on the action param and rowID received...
 * @param action...
 */
function promotionRequest(category, subcategory, promotionID, action, rowID)
{
	//alert('promo Request: ' + category + ' ' + subcategory + ' ' + promotionID + ' ' + action + ' ' + rowID);
	
	var $result = true;
	
	if(action === 'request')
	{
		//action 0=Select,  1=Add Store, 2=Delete Store
		var vAction = document.getElementById('action').selectedIndex;
		if(vAction === 0)
		{
			$result = false;
		}
		else
		{
			if(vAction === 1)
			{
				action = 'add';
			}
			else
			{
				action = 'delete';
			}			
		}

		//rowID 0=Select
		var vRowID = document.getElementById('rowID').selectedIndex;
		if(vRowID === 0)
		{
			$result = false;
		}
		else
		{
			rowID = vRowID;			
		}	
	}

	if($result)
	{
		//alert('promo Request: ' + category + ' ' + subcategory + ' ' + promotionID + ' ' + action + ' ' + storeID);
		
		request = new promoContent(category, subcategory, promotionID, action, rowID);
		
		var array = request.sendArray();
		//alert('returned: '+ array );
		if(array != false)
		{
			request.sendRequest(array);
		}			
	}
	else
	{
		alert('Both Action and Store Options must be Selected!');
	}
}

/*
function GetPullDownValues(pd) 
{
		var values = new Array(parseInt(pd[pd.selectedIndex].value), pd[pd.selectedIndex].text);
		return values;
}

function GetPullDownValues2(pd) 
{
		var values = new Array((pd[pd.selectedIndex].value), pd[pd.selectedIndex].text);
		return values;
}*/

/**
 * This Object Sets Request for Promotion Content via AJAX
 * @param action
 * @returns {setContent}
 */
function promoContent(category, subcategory, promotionID, action, rowID) 
{
	this.category = category;
	this.subcategory = subcategory;
	this.promotionID = promotionID;
	this.action = action;
	this.rowID = rowID;
}

promoContent.prototype.sendArray = function() 
{
	//alert('sendArray');
	return [this.category, this.subcategory, this.promotionID, this.action, this.rowID];
}

/**
 * This Object Sets Request Page Content via AJAX
 * @param action
 * @returns {setContent}
 */
function setContent(category, action, rowID) 
{
	//alert('set Content!');
	this.category = category;
	this.action = action;
	this.rowID = rowID;
	
	//Setup if action equals stores
	if(this.category == 'stores')
	{
		this.Name; 	 	
		this.vLat; 	
		this.vLong;  
		this.Town;
		this.callRows = ['updateStore','saveStore'];		
	}

	//Setup if action equals staff
	if(this.category == 'staff')
	{
		this.Username; 	 	
		this.Password; 	
		this.User_Role_ID;  
		this.Name;
		this.Surname;
		this.Mobile;		
		this.callRows = ['updateStaff','saveStaff'];
	}
	
	//Setup if action equals promotions
	if(this.category == 'promotions')
	{
		this.Promotion; 	 	
		this.daydate; 
		this.monthdate;
		this.yeardate;
		this.callRows = ['updatePromotion','savePromotion'];
	}	
	
	//this.talk = function() {
	//	alert( this.name + " say meeow!" )
	//}
	
	//alert('SET:: ' + this.category + ' ' + this.action + ' ' + this.rowID);
	//alert('SET::: ' + this.callRows );
}

/**
 * Set properties to Params Received ...
 *//*
setContent.prototype.setProperties = function(name, vlat, vlong, town) 
{
	alert('here');
	this.Name = name;
	this.Lat = vlat;
	this.vLong = vlong;
	this.Town = town;
	alert('done setting...');
}*/

setContent.prototype.sendArray = function() 
{
	/*alert(this.action);
	alert(isset(this.Town));
	alert(isset(this.Surname));
	alert('DUH');
	if(this.action === 'stores')
	{
		alert('Yes');
	}
	*/
	
	//alert('SendArray ');
	
	if(this.action === 'logout')
	{
		return [this.category, this.action, this.rowID];
	}
	else
	{
		//alert('checking for this ' + this.action +' in this '+ this.callRows);
		//if()
		var decide = this.callRows.indexOf(this.action); 
		//alert('findings: '+decide);
		//alert(this.category + this.action + this.rowID);
		if(decide === -1)
		{
			if(this.category === 'stores')
			{
				//alert('Class Values: ' + this.category + ' ' + this.action + ' ' + this.rowID);
				return [this.category, this.action, this.rowID, this.Name, this.vLat, this.vLong, this.Town];
			}
			if(this.category === 'staff')
			return [this.category, this.action, this.rowID, this.Username, this.Password, this.User_Role_ID, this.Name, this.Surname, this.Mobile];
			if(this.category === 'promotions')
			return [this.category, this.action, this.rowID, this.Promotion, this.daydate, this.monthdate, this.yeardate];
		}
		else
		{
			
			var validateContent = content.checkContentData();
			
			//alert('after check  '+ validateContent);
			//alert(this.category+ ' ' +this.action+ ' ' +this.rowID+ ' ' +this.Promotion+ ' ' +this.daydate+ ' ' +this.monthdate+ ' ' +this.yeardate);
			
			if(validateContent)
			{
				if(this.category === 'stores')
				return [this.category, this.action, this.rowID, this.Name, this.vLat, this.vLong, this.Town];
				if(this.category === 'staff')
				{
					if(this.User_Role_ID === 0)
					{
						alert('One of the fields maybe empty, Please verify and re-submit!');
						return false;
					}
					else
					{
						return [this.category, this.action, this.rowID, this.Username, this.Password, this.User_Role_ID, this.Name, this.Surname, this.Mobile];
					}
				}
				if(this.category === 'promotions')
				return [this.category, this.action, this.rowID, this.Promotion, this.daydate, this.monthdate, this.yeardate];			
			}
			else
			{
				alert('One of the fields maybe empty, Please verify and re-submit!');
				return false;					
			}
		}		
	}
}


/**
 * Validate Store Data ...
 * @returns validated Store data else Error message...
 */
setContent.prototype.checkContentData = function()
{
	//alert('checkContentData');
	
	if(this.category === 'stores')
	{
		this.Name = document.getElementById('name').value;
		this.vLat = document.getElementById('lat').value;
		this.vLong = document.getElementById('long').value;
		this.Town = document.getElementById('town').value;
		
		//setContent.setProperties(name, vlat, vlong, town);
		//alert('Do not Return!');
		
		var myStringArray = [this.Name, this.vLat, this.vLong, this.Town];
		
		//alert(myStringArray);
	
		return checkArray(myStringArray);
	}
	
	if(this.category === 'staff')
	{
		//Name, Surname, Mobile, Role, Username, Password
		//this.User_Role_ID; 
		this.Name = document.getElementById('name').value;
		this.Surname = document.getElementById('surname').value;
		this.Mobile = document.getElementById('mobile').value;
		this.User_Role_ID = document.getElementById('Role').selectedIndex;
		this.Username = document.getElementById('username').value;
		this.Password = document.getElementById('password').value;
		
		//setContent.setProperties(name, vlat, vlong, town);
		//alert('Do not Return!');
		
		var myStringArray = [this.Name, this.Surname, this.Mobile, this.Username, this.Password];
		
		//alert(myStringArray);
	
		return checkArray(myStringArray);			
	}	
	
	if(this.category === 'promotions')
	{
		this.Promotion = document.getElementById('promotion').value;
		this.daydate = document.getElementById('daydate').value;
		this.monthdate = document.getElementById('monthdate').value;
		this.yeardate = document.getElementById('yeardate').value;

		//setContent.setProperties(name, vlat, vlong, town);
		//alert('Do not Return!');
		
		var myStringArray = [this.Promotion, this.daydate, this.monthdate, this.yeardate];
		
		//alert(myStringArray);
	
		return checkArray(myStringArray);		
	}
}

function checkArray(my_arr){
	   for(var i=0;i<my_arr.length;i++){
	       if(my_arr[i] === "")   
	          return false;
	   }
	   return true;
}


/**
 * Send Ajax Request for Page content ...
 * @param array
 * @returns Content Data
 */
setContent.prototype.sendRequest = function(array) 
//function sendRequest(array)
{
	//alert(array);
	var progress = '<img width=30 height=30 src="Images/32-0.gif">';
	//showhide('ProgressIndicator',1); 
	document.getElementById('ProgressIndicator').innerHTML = progress;
    
	var d = new Date();
    		
    http.open('get', 'Process/Process.php?array='+array+"&neverused="+d.getTime());
    http.onreadystatechange = handleResponse;
    http.send(null);
}

function handleResponse() {
   if(http.readyState == 4){
 	  var progress = '';
	  document.getElementById('ProgressIndicator').innerHTML = progress;	   
      var response = http.responseText;
      var contentParts = response.split('|');
      if(contentParts[1])
      {
          if(contentParts[1] === 'Refresh')
          {
        	  window.location.href='index.php';
          }
          else
          {
        	  document.getElementById('Selection').innerHTML = contentParts[0];
        	  if(contentParts[1] !== 'None')
        	  {
        		  document.getElementById('Content').innerHTML = contentParts[1];
          	  }
        	  else
        	  {
        		  document.getElementById('Content').innerHTML = '';
        	  }
          }    	  
      }
      else
      {
    	  document.getElementById('Selection').innerHTML = '';
    	  document.getElementById('Content').innerHTML = contentParts[0];
      }
   }
}

///request
/**
 * Send Ajax Request for Page content ...
 * @param array
 * @returns Content Data
 */
promoContent.prototype.sendRequest = function(array) 
{
	//alert('sending....' + array);
	var progress = '<img width=30 height=30 src="Images/32-0.gif">';
	//showhide('ProgressIndicator',1); 
	document.getElementById('ProgressIndicator').innerHTML = progress;
    
	var d = new Date();
    		
    http.open('get', 'Process/Process.php?array='+array+"&neverused="+d.getTime());
    http.onreadystatechange = handlePromotionResponse;
    http.send(null);
}

function handlePromotionResponse() {
   if(http.readyState == 4){
 	  var progress = '';
	  document.getElementById('ProgressIndicator').innerHTML = progress;	   
      var response = http.responseText;
      //document.getElementById('Content').innerHTML = response;
      var contentParts = response.split('|');
      
      if(contentParts[1])
      {
          if(contentParts[1] == 'Refresh')
          {
        	  window.location.href='index.php';
          }
          else
          {
        	  document.getElementById('Selection').innerHTML = contentParts[0];
        	  if(contentParts[1] !== 'None')
        	  {
        		  document.getElementById('Content').innerHTML = contentParts[1];
        	  }
        	  else
        	  {
        		  document.getElementById('Content').innerHTML = '';
        	  }
          }    	  
      }
      else
      {
    	  document.getElementById('Selection').innerHTML = '';
    	  document.getElementById('Content').innerHTML = contentParts[0];
      }
   }
}

/**
 * This validates numbers and email inputs based upon the params...
 * @param action...
 */
function verifyValue(elementID, elementType)
{
	//alert('Hey : ' + elementID+ ' ' +elementType);
	checkContent = new setValues(elementID, elementType);
		
	checkContent.validateContent();
}

/**
 * This Object Sets Element Values for Validation....
 * @param action
 */
function setValues(elementID, elementType) 
{
	this.elementID = elementID;
	this.elementType = elementType;
	if(elementType != 'storeSearch')
	this.elementContent = document.getElementById(this.elementID).value;
}


setValues.prototype.validateContent = function()  
{
	
	//alert(this.elementType);
	if(this.elementType === 'number')
	{
		var result = checkContent.validateNumber();
		
		if(result)
		{
			document.getElementById(this.elementID).className="error";
		}
		else
		{
			document.getElementById(this.elementID).className="";
		}
	}
	
	if(this.elementType === 'email')
	{
		var result = checkContent.validateEmail();
		
		if(result)
		{
			document.getElementById(this.elementID).className="";
		}
		else
		{
			document.getElementById(this.elementID).className="error";
		}
	}
	
	if(this.elementType === 'username')
	{
		//alert('then : '+this.elementType);
		var array = [this.elementType,this.elementContent];
		checkContent.sendRequest(array);
	}	
	
	if(this.elementType === 'storeSearch')
	{
		//document.getElementById('searchMessage').className="";
		//alert(document.getElementById('searchMessage').innerHTML);
		//var array = [this.elementType,this.elementContent];
		//alert(array);
		//checkContent.sendRequest(array);
		//alert('Show');
		//showhide('searchMessage',1);
	}	
}

/**
 * Validate Numbers....
 * @param value
 * @returns boolean status
 */
setValues.prototype.validateNumber = function() 
{
	return isNaN(this.elementContent);
}

/**
 * Validate Email ....
 * @param value
 * @returns boolean status
 */
setValues.prototype.validateEmail = function() 
{
	 var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	 return re.test(this.elementContent);	
}

/**
 * Send Ajax Request for Value Validation and Comparison ...
 * @param array
 * @returns Response (Boolean)
 */
setValues.prototype.sendRequest = function(array) 
{
	//alert('asking: '+array);
	//var progress = '<img width=30 height=30 src="Images/32-0.gif">';
	var progress = '&nbsp;&nbsp;<img src="Images/valueCheck.gif">';
	//showhide('ProgressIndicator',1); 
	document.getElementById('checkUsername').innerHTML = progress;
    
	var d = new Date();
    		
    http.open('get', 'Process/Process.php?array='+array+"&neverused="+d.getTime());
    http.onreadystatechange = setValuesHandleResponse;
    http.send(null);
}

function setValuesHandleResponse() {
   if(http.readyState == 4){
 	  var progress = '';
	  document.getElementById('checkUsername').innerHTML = progress;	   
      var response = http.responseText;
      if(response === 'true')
      {
    	  document.getElementById('username').className="error";
    	  document.getElementById('checkUsername').innerHTML = 'Username is not available!';
      }
      else
	  {
    	  document.getElementById('username').className="";	 
    	  document.getElementById('checkUsername').innerHTML = 'Ok';
	  }
   }
}

function showhide(div,on) 
{
	  document.getElementById(div).style.display=(on)?'block':'none';
}
