/*
 * This Section is responsible for creatting 
 * Ajax Object.
 */
//promotionRequest
//HERE
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
  var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
}

var cStore;
var cUser;
var cPromotion;
var cStamp;

function requestCall(Store,User, Promotion, Stamp)
{
	//alert('Store: '+Store+'   User: '+User+'   Promotion: '+Promotion+'   Stamp: '+Stamp);
	
	cStore = Store;
	cUser = User;
	cPromotion = Promotion;
	cStamp = Stamp

	if (navigator.geolocation) {
		//alert('Geo Location is Supported!!!!!!!!!!!!');
	  navigator.geolocation.getCurrentPosition(success);
	} else {
		//error('Geo Location is not supported');
	  alert('Geo Location is not supported');
	}	
	
	if(navigator.geolocation)
	{
		navigator.geolocation.getCurrentPosition(showPosition);
	}
	else
	{
		alert("Geolocation is not supported by this browser.");
	}	
}

function showPosition(pos){
	//alert(cStore + " Latitude: "+pos.coords.latitude+"\nLongitude: "+pos.coords.longitude);
	//alert('going...');
	var action = "lat-long";
	var array = [action,pos.coords.latitude,pos.coords.longitude,cStore,cUser,cPromotion,cStamp];
	sndReq(array);	
}
	
function sndReq(array) {
	var indicatorID = array[6]+''+array[5]+''+array[3]+''+array[4];
	var progress = '&nbsp;&nbsp;<img src="Images/valueCheck.gif">';
	document.getElementById(indicatorID).innerHTML = progress;
    
	var d = new Date();
    		
	http.open('get', 'Process/Process.php?array='+array+"&neverused="+d.getTime());
    http.onreadystatechange = handleGPSResponse;
    http.send(null);
}

function handleGPSResponse() {
	//alert('checking response!');
     if(http.readyState == 4){
          var response = http.responseText;
          //alert('response... '+ response);
          //if(response === 'true')
         // {
        	  //alert(response);
        	  //document.getElementById('Indicator').innerHTML = 'Ok';
          //}
          //else
	      //{
        	  //alert(response); 
        	  document.getElementById('Content').innerHTML = response;
	      //}
               
    }
     /*
    if(http.readyState == 0)
    {
    	 alert('request not initialized');
    }
    
    if(http.readyState == 1)
    {
    	 alert('server connection established');
    }  
    
    if(http.readyState == 2)
    {
    	 alert('request received');
    }
    
    if(http.readyState == 3)
    {
    	 alert('processing request');
    }  */  

}

function OpenInNewTab(url)
{
	alert(url );
	//var GOurl = 'rx_'+url+'.php';
	//var win=window.open(GOurl, '_blank');
	//win.focus();
}

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
	
	var status;
	
	if(action == 'deleteStaff')
	{
		
		  var x = confirm("Are you sure you want to delete?");
		  if(x)
		  {
			  status = true;
		  }
		  else
		  {
			  status = false;		
		  }
	}
	else
	{
		status = true;
	}

	if(status)
	{
		content = new setContent(category, action, rowID);
		
		var array = content.sendArray();
		
		//alert('Array to send: '+ array);
		
		if(array != false)
		{
			//alert('Sending : ' +array);
			content.sendRequest(array);
		}
	}
}

/**
 * This function requests Page Content [Promotion Related] based on the action param and rowID received...
 * @param action...
 */
function promotionRequest(category, subcategory, promotionID, action, rowID)
{
	//alert('promo Request: ' + category + ' ' + subcategory + ' ' + promotionID + ' ' + action + ' ' + rowID);
	//                        promotionRe      urlStaff            1                    list            0
	var $result = true;
	
	if(action === 'request')
	{
		//action 0=Select,  1=Add Store, 2=Delete Store
		var vAction = GetPullDownValue('action');
		
		//alert('vAction '+vAction);
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
		var vRowID = GetPullDownValue('rowID');
		
		//alert('vRowID '+vRowID);
		if(vRowID === 0)
		{
			$result = false;
		}
		else
		{
			rowID = vRowID;			
		}	
		//alert('promo Request: ' + category + ' ' + subcategory + ' ' + promotionID + ' ' + action + ' ' + rowID);
	}

	if($result)
	{
		//alert('promo Request2: ' + category + ' ' + subcategory + ' ' + promotionID + ' ' + action + ' ' + storeID);
		
		request = new promoContent(category, subcategory, promotionID, action, rowID);
		
		var array = request.sendArray();
		//alert('returned: SENding.... '+ array );
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

function GetPullDownValue(pd) 
{
	var pullDownElement = document.getElementById(pd);
	var values = GetPullDownValues(pullDownElement);
	return values[0];	
}

function GetPullDownValues(pd) 
{
		var values = new Array(parseInt(pd[pd.selectedIndex].value), pd[pd.selectedIndex].text);
		return values;
}

function GetPullDownValues2(pd) 
{
		var values = new Array((pd[pd.selectedIndex].value), pd[pd.selectedIndex].text);
		return values;
}

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
	if(this.category == 'urls')
	{
		this.Name; 	 	
		this.Url; 	
		this.callRows = ['updateUrl','saveUrl'];		
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
	
	//Setup if action equals calls
	if(this.category == 'calls')
	{
		this.dayF; 
		this.monthF;
		this.yearF;	
		
		this.dayT; 
		this.monthT;
		this.yearT;	

		this.callRows = ['report'];		
	}	
	
	if(this.category == 'report')
	{
		this.callRows = ['none'];		
	}	
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
	//alert('SendArray ' + this.action);
	
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
			if(this.category === 'urls')
			{
				//alert('Class Values: ' + this.category + ' ' + this.action + ' ' + this.rowID);
				return [this.category, this.action, this.rowID, this.Name, this.Url];
			}
			if(this.category === 'staff')
			return [this.category, this.action, this.rowID, this.Username, this.Password, this.User_Role_ID, this.Name, this.Surname, this.Mobile];
			if(this.category === 'promotions')
			return [this.category, this.action, this.rowID, this.Promotion, this.daydate, this.monthdate, this.yeardate];
			if(this.category === 'calls')
				return [this.category, this.action, this.rowID, this.dayF, this.monthF, this.yearF, this.dayT, this.monthT, this.yearT];
			if(this.category === 'report')
				return [this.category, this.action, this.rowID];
		}
		else
		{
			
			var validateContent = content.checkContentData();
			
			//alert('after check  '+ validateContent);
			//alert(this.category+ ' ' +this.action+ ' ' +this.rowID+ ' ' +this.Promotion+ ' ' +this.daydate+ ' ' +this.monthdate+ ' ' +this.yeardate);
			
			if(validateContent)
			{
				if(this.category === 'urls')
				return [this.category, this.action, this.rowID, this.Name, this.Url];
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
				if(this.category === 'calls')
				return [this.category, this.action, this.dayF, this.monthF, this.yearF, this.dayT, this.monthT, this.yearT];
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
	
	if(this.category === 'urls')
	{
		this.Name = document.getElementById('name').value;
		this.Url = document.getElementById('url').value;
		
		//setContent.setProperties(name, vlat, vlong, town);
		//alert('Do not Return!');
		
		var myStringArray = [this.Name, this.Url];
		
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
	
	if(this.category === 'calls')
	{
		this.dayF = document.getElementById('dayF').value;
		this.monthF = document.getElementById('monthF').value;
		this.yearF = document.getElementById('yearF').value;
		this.dayT = document.getElementById('dayT').value;
		this.monthT = document.getElementById('monthT').value;
		this.yearT = document.getElementById('yearT').value;		

		//setContent.setProperties(name, vlat, vlong, town);
		//alert('Do not Return!');
		
		var myStringArray = [this.dayF, this.monthF, this.yearF, this.dayT, this.monthT, this.yearT];
		
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
	//alert('HERE ' +array);
	var progress = '<img width=30 height=30 src="Images/32-0.gif">';
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
      //alert('res: '+ response);
      var contentParts = response.split('|');
      //alert(contentParts);
      if(contentParts[1])
      {
          if(contentParts[1] === 'Refresh')
          {
        	  window.location.href='index.php';
          }
          else
          {
        	  document.getElementById('Selection').innerHTML = contentParts[0];
        	  if(contentParts[1] !== '')
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
	//alert('HERE: ');
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
        	  if(contentParts[1] !== '')
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



function fileUpload(form, action_url, div_id) {
    // Create the iframe...
    var iframe = document.createElement("iframe");
    iframe.setAttribute("id", "upload_iframe");
    iframe.setAttribute("name", "upload_iframe");
    iframe.setAttribute("width", "0");
    iframe.setAttribute("height", "0");
    iframe.setAttribute("border", "0");
    iframe.setAttribute("style", "width: 0; height: 0; border: none;");
 
    // Add to document...
    form.parentNode.appendChild(iframe);
    window.frames['upload_iframe'].name = "upload_iframe";
 
    iframeId = document.getElementById("upload_iframe");
 
    // Add event...
    var eventHandler = function () {
 
            if (iframeId.detachEvent) iframeId.detachEvent("onload", eventHandler);
            else iframeId.removeEventListener("load", eventHandler, false);
 
            // Message from server...
            if (iframeId.contentDocument) {
                content = iframeId.contentDocument.body.innerHTML;
            } else if (iframeId.contentWindow) {
                content = iframeId.contentWindow.document.body.innerHTML;
            } else if (iframeId.document) {
                content = iframeId.document.body.innerHTML;
            }
 
            document.getElementById(div_id).innerHTML = content;
            
            
 
            // Del the iframe...
            setTimeout('iframeId.parentNode.removeChild(iframeId)', 250);
        }
 
    if (iframeId.addEventListener) iframeId.addEventListener("load", eventHandler, true);
    if (iframeId.attachEvent) iframeId.attachEvent("onload", eventHandler);
 
    // Set properties of form...
    form.setAttribute("target", "upload_iframe");
    form.setAttribute("action", action_url);
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("encoding", "multipart/form-data");
 
    // Submit the form...
    form.submit();
 
    document.getElementById(div_id).innerHTML = "Uploading...";
    
    
    
}

//upload code raw
function redirect()
{
	//alert('redirect');
	
	document.getElementById('my_form').target = 'my_iframe'; //'my_iframe' is the name of the iframe
	document.getElementById('my_form').submit();
	
	var iFrame = document.getElementById("my_iframe");
	var loading = document.getElementById("loading");
	iFrame.style.display = "none";
	iFrame.contentDocument.getElementsByTagName("body")[0].innerHTML = "";
	loading.style.display = "block";
	checkComplete();
}

var checkComplete = function()
{
	var iFrame = document.getElementById("my_iframe").contentDocument.getElementsByTagName("body")[0];
	var loading = document.getElementById("loading");

	if(iFrame.innerHTML == "")
	{
	setTimeout ( checkComplete, 2000 );
	}
	else
	{
		if(iFrame.innerHTML == "success")
		{		
			////loading.style.display = "none";
			////document.getElementById("my_iframe").style.display = "block";
			//successful do something here!
			////alert('successful do something here!');
			//document.getElementById("Content").innerHTML = 'successful do something here!';
			array = ['Schedule', 'Content', cUser];
			sendRequest(array) 
		}
		else
		{
			loading.style.display = "none";
			alert("Error: "+ iFrame.innerHTML);
		}
	}
}

//Upload Unique working Directory Content Request

function sendRequest(array) 
{
	var progress = '<img width=30 height=30 src="Images/32-0.gif">';
	document.getElementById('ProgressIndicator').innerHTML = progress;
    
	var d = new Date();
    		
    http.open('get', 'Process/Process.php?array='+array+"&neverused="+d.getTime());
    http.onreadystatechange = handlePromotionResponseUser;
    http.send(null);
}

function handlePromotionResponseUser() {
   if(http.readyState == 4){
 	  var progress = '';
	  document.getElementById('ProgressIndicator').innerHTML = progress;	   
      var response = http.responseText;
    	  document.getElementById('Container').innerHTML = response;
   }
}

/*
function showImage(Image)
{
	//alert(Image);
	TINY.box.show('Process/ShowImage.php?call='+Image,1,500,255,1);
}*/



//region Search Tool Indexer
function showImage(Call,width,height)
{
	var w_width = parseInt(width)  + 30;
	var w_height = parseInt(height) + 30;
	
	var win= null;
	OpenNewWindow('Process/ShowImage.php?call='+Call,w_width,w_height,'Loading');
}

function OpenNewWindow(mypage,w,h,myname)
{
	var winl = (screen.width-w)/2;
	var wint = (screen.height-h)/2;
	//settings='height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars=no,toolbar=no';
	settings='height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars=yes,toolbar=no';
	win=window.open(mypage,myname,settings)
	if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}

function init() 
{
	//document.getElementsByTagName('input')[0].focus();"
	alert("loaded!");
}

//////////////////////////////////////TINY BOX//////////////////////////////////////

var TINY={};

function T$(i){return document.getElementById(i)}

TINY.box=function(){
	var p,m,b,fn,ic,iu,iw,ih,ia,f=0;
	return{
		show:function(c,u,w,h,a,t){
			if(!f){
				p=document.createElement('div'); p.id='tinybox';
				m=document.createElement('div'); m.id='tinymask';
				b=document.createElement('div'); b.id='tinycontent';
				document.body.appendChild(m); document.body.appendChild(p); p.appendChild(b);
				m.onclick=TINY.box.hide; window.onresize=TINY.box.resize; f=1
			}
			if(!a&&!u){
				p.style.width=w?w+'px':'auto'; p.style.height=h?h+'px':'auto';
				p.style.backgroundImage='none'; b.innerHTML=c
			}else{
				b.style.display='none'; p.style.width=p.style.height='100px'
			}
			this.mask();
			ic=c; iu=u; iw=w; ih=h; ia=a; this.alpha(m,1,80,3);
			if(t){setTimeout(function(){TINY.box.hide()},1000*t)}
		},
		fill:function(c,u,w,h,a){
			if(u){
				p.style.backgroundImage='';
				var x=window.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
				x.onreadystatechange=function(){
					if(x.readyState==4&&x.status==200){TINY.box.psh(x.responseText,w,h,a)}
				};
				x.open('GET',c,1); x.send(null)
			}else{
				this.psh(c,w,h,a)
			}
		},
		psh:function(c,w,h,a){
			if(a){
				if(!w||!h){
					var x=p.style.width, y=p.style.height; b.innerHTML=c;
					p.style.width=w?w+'px':''; p.style.height=h?h+'px':'';
					b.style.display='';
					w=parseInt(b.offsetWidth); h=parseInt(b.offsetHeight);
					b.style.display='none'; p.style.width=x; p.style.height=y;
				}else{
					b.innerHTML=c
				}
				this.size(p,w,h)
			}else{
				p.style.backgroundImage='none'
			}
		},
		hide:function(){
			TINY.box.alpha(p,-1,0,3)
		},
		resize:function(){
			TINY.box.pos(); TINY.box.mask()
		},
		mask:function(){
			m.style.height=TINY.page.total(1)+'px';
			m.style.width=''; m.style.width=TINY.page.total(0)+'px'
		},
		pos:function(){
			var t=(TINY.page.height()/2)-(p.offsetHeight/2); t=t<10?10:t;
			p.style.top=(t+TINY.page.top())+'px';
			p.style.left=(TINY.page.width()/2)-(p.offsetWidth/2)+'px'
		},
		alpha:function(e,d,a){
			clearInterval(e.ai);
			if(d==1){
				e.style.opacity=0; e.style.filter='alpha(opacity=0)';
				e.style.display='block'; this.pos()
			}
			e.ai=setInterval(function(){TINY.box.ta(e,a,d)},20)
		},
		ta:function(e,a,d){
			var o=Math.round(e.style.opacity*100);
			if(o==a){
				clearInterval(e.ai);
				if(d==-1){
					e.style.display='none';
					e==p?TINY.box.alpha(m,-1,0,2):b.innerHTML=p.style.backgroundImage=''
				}else{
					e==m?this.alpha(p,1,100):TINY.box.fill(ic,iu,iw,ih,ia)
				}
			}else{
				var n=Math.ceil((o+((a-o)*.5))); n=n==1?0:n;
				e.style.opacity=n/100; e.style.filter='alpha(opacity='+n+')'
			}
		},
		size:function(e,w,h){
			e=typeof e=='object'?e:T$(e); clearInterval(e.si);
			var ow=e.offsetWidth, oh=e.offsetHeight,
			wo=ow-parseInt(e.style.width), ho=oh-parseInt(e.style.height);
			var wd=ow-wo>w?0:1, hd=(oh-ho>h)?0:1;
			e.si=setInterval(function(){TINY.box.ts(e,w,wo,wd,h,ho,hd)},20)
		},
		ts:function(e,w,wo,wd,h,ho,hd){
			var ow=e.offsetWidth-wo, oh=e.offsetHeight-ho;
			if(ow==w&&oh==h){
				clearInterval(e.si); p.style.backgroundImage='none'; b.style.display='block'
			}else{
				if(ow!=w){var n=ow+((w-ow)*.5); e.style.width=wd?Math.ceil(n)+'px':Math.floor(n)+'px'}
				if(oh!=h){var n=oh+((h-oh)*.5); e.style.height=hd?Math.ceil(n)+'px':Math.floor(n)+'px'}
				this.pos()
			}
		}
	}
}();

TINY.page=function(){
	return{
		top:function(){return document.documentElement.scrollTop||document.body.scrollTop},
		width:function(){return self.innerWidth||document.documentElement.clientWidth||document.body.clientWidth},
		height:function(){return self.innerHeight||document.documentElement.clientHeight||document.body.clientHeight},
		total:function(d){
			var b=document.body, e=document.documentElement;
			return d?Math.max(Math.max(b.scrollHeight,e.scrollHeight),Math.max(b.clientHeight,e.clientHeight)):
			Math.max(Math.max(b.scrollWidth,e.scrollWidth),Math.max(b.clientWidth,e.clientWidth))
		}
	}
}();

function dropDownReset(elementId)
{
	var selObj = document.getElementById(elementId);
	selObj.selectedIndex = 0;
	TINY.box.hide();
}

function dropDownSustain()
{
	TINY.box.hide();
}

//////////////////////////////////////TINY BOX//////////////////////////////////////
