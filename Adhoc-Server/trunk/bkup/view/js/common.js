function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}
function echeck(str) {

	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	if(str.match(emailExp)){
		return true;
	}else{
		return false;
	}
}
function CompareDates(fromDate,toDate)
{
   var dt1   = parseInt(fromDate.substring(8,10),10);
   var mon1  = parseInt(fromDate.substring(5,7),10);
   var yr1   = parseInt(fromDate.substring(0,4),10);
   var dt2   = parseInt(toDate.substring(8,10),10);
   var mon2  = parseInt(toDate.substring(5,7),10);
   var yr2   = parseInt(toDate.substring(0,4),10);
   var date1 = new Date(yr1, mon1, dt1);
   var date2 = new Date(yr2, mon2, dt2);

   if(date2 < date1)
   {
      return false;
   }
   else
   {
      return true;
   }
}
function Redirect(path)
{
	window.location = path;
}
function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}
var minYear = 1900;
var maxYear=2100;
function isDate(dtStr,first,second,third,dtCh){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	
	if(first == 'm'){
		var strMonth=dtStr.substring(0,pos1)
	}else if(first == 'd'){
		var strDay=dtStr.substring(0,pos1)
	}else{
		var strYear=dtStr.substring(0,pos1)
	}
	
	if(second == 'm'){
		var strMonth=dtStr.substring(pos1+1,pos2)
	}else if(second == 'd'){
		var strDay=dtStr.substring(pos1+1,pos2)
	}else{
		var strYear=dtStr.substring(pos1+1,pos2)
	}
	
	if(third == 'm'){
		var strMonth=dtStr.substring(pos2+1)
	}else if(third == 'd'){
		var strDay=dtStr.substring(pos2+1)
	}else{
		var strYear=dtStr.substring(pos2+1)
	}
	
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
		alert("The date format should be :yyyy-mm-dd")
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
		alert("Please enter a valid month")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("Please enter a valid day")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("Please enter a valid date")
		return false
	}
	return true;
}
function newWindow(mypage, myname, w, h, scroll) {
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;

    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function submitPage(pageNo)
{   
	theForms = document.getElementsByTagName("form");
	formName = theForms[0].name;
	
    if(formName == "frm_post"){ formName = theForms[1].name;}

	queryString = window.location.search.substring(1);
	qArray = queryString.split("&");
	total = qArray.length;
	var qString = "";
	var pageNoCame = false;
	i = 0;
	
	while(i < total)
	{		
		key = qArray[i].split("=");	
		
		if (key[0] == 'pageno')
		{
			if (i == (total-1))
				qString = qString + key[0] + "=" + pageNo;
			else
				qString = qString + key[0] + "=" + pageNo + "&";
				
			pageNoCame = true;
			i++;
		}
		else
		{
			if (i == (total-1))
				qString = qString + qArray[i];
			else
				qString = qString + qArray[i] + "&";
				
			i++;
		}			
	}
		if (pageNoCame == false)
			qString = qString + "&pageno=" + pageNo;
	
	
	//qString = qString + extraVARs;	
	eval("document."+formName+".action='?"+qString+"'");	
	eval("document."+formName+".submit();");
	
}

function check_uncheck_All(controlName1,controlName2)
{		
	chks = eval("document.getElementsByName('"+controlName2+"')");		
	i = 0;
	total_chks = chks.length;	
	while (i < total_chks)
	{		
		if (eval("document.getElementById('"+controlName1+"')").checked == true)
			chks[i].checked = true;
		else
			chks[i].checked = false;
		i++;
	}
}

function sortSubmit(formName,sortBy)
{

	if (document.getElementById("sort_order").value == 'DESC')
	{
	 	document.getElementById("sort_order").value = 'ASC';	
	}			
	else if (document.getElementById("sort_order").value == 'ASC')
	{
	 	document.getElementById("sort_order").value = 'DESC';	
	}
	 

	queryString = window.location.search.substring(1);
	
	document.getElementById("sort_by").value = sortBy;
	eval("document."+formName+".action = '?"+ queryString+"'");
	eval("document."+formName+".submit()");
}

function cancelForm(url)
{
    window.location = url;
}


/* AJAX functions*/
    // intialize objects
	function GetAjax() {
		var xmlhttp;
		if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
			try {
				xmlhttp = new XMLHttpRequest();
			} catch (e) {
				xmlhttp = false;
			}
		}
		return xmlhttp;
	}

	// call action
	function Ajax(get){
		var oAjax = GetAjax();
		var url = "./ajax_functions.php?" + get;
		oAjax.open("GET", url, false);
		oAjax.send(null);

		if((oAjax.readyState == 4) && (oAjax.status == 200)){
			var r = unescape(oAjax.responseText);
			return r;

		}
		else {
			/* PROCESOS ALTERNOS */
		}
	}

    // Fetch fields information
    function getFieldsInformation(id, action, checks){
		var get = "action=" + action + "&id=" + id;
		data = Ajax(get);

		var data_cehck = checks.split(',');

		divname = data_cehck[0];
        fieldname = data_cehck[1];

		document.getElementById(divname).innerHTML = '';
		if(data != "NOK"){
			var l = data.split('|*|');
			if(l.length > 0){
				for(i in l){
					var d = l[i].split('[:?]');
					if(d[1] != null){
						document.getElementById(divname).innerHTML += '<div class="ccheck"><input name="' + fieldname+ '[' + unescape(d[0].replace(/\+/g, ' ')) + ']" id="'+ fieldname+ '_' + unescape(d[0].replace(/\+/g, ' ')) + '" value="' + unescape(d[0].replace(/\+/g, ' ')) + '" type="checkbox"/> ' + unescape(d[1].replace(/\+/g, ' ', ' ')) + '</div>';
					}
				}
			}
		}

    	return false;
	}

