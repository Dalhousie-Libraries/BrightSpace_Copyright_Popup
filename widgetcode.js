<script>// <![CDATA[
// MODIFY
var server = "LOCATION_OF_PHP_APPLICATION"; //eg. "util.library.dal.ca/brightspace-copyright-app"
// END MODIFY
// DO NOT MODIFY - THESE ARE NEEDED BY D2L
var username = '{UserName}'; //These are D2L replacement Strings
var uid = '{UserId}'; //These are D2L replacement String
// END DO NOT MODIFY

function displayFacultyNotice(){
  var overlay = document.createElement("div");
  overlay.setAttribute("id","overlay");
  overlay.setAttribute("class", "overlay");
  overlay.setAttribute("style", "padding: 5px; background-color: #b9b9b9; z-index: 1000; width: 100%; height: 100%; position: fixed; top: 0; left: 0; filter: alpha(opacity=70); opacity: .70;");
  document.body.appendChild(overlay);
  var overlay2 = document.createElement("div");
  overlay2.setAttribute("id","overlay2");
  overlay2.setAttribute("class", "overlay2");
  overlay2.setAttribute("style", "z-index: 1001; padding: 20px; margin: 0 auto; width: 600px; height: 375px; background-color: #ffffff; position: fixed; top: 50px; left: 50px; right: 50px");
  overlay2.innerHTML = document.getElementById("copyrightnoticefaculty").innerHTML;
  document.body.appendChild(overlay2);
}
function hideNotice(type){
  var xmlhttp;
  var isIE8 = window.XDomainRequest ? true : false;

  if(isIE8){
    xmlhttp=new window.XDomainRequest();
  }
  else if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  else{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  if(isIE8){
    xmlhttp.onload = function(){
	  document.body.removeChild(document.getElementById("overlay"));
	  document.body.removeChild(document.getElementById("overlay2"));
    }
  }
  else{
    xmlhttp.onreadystatechange=function(){
  	  if (xmlhttp.readyState==4 && xmlhttp.status==200){
  	    document.body.removeChild(document.getElementById("overlay"));
  	    document.body.removeChild(document.getElementById("overlay2"));
  	  }
    }
  }

  xmlhttp.open("GET","https://"+ server + "/accept.php?uid=" + uid + "&uname=" + username + "&type=" + type + "&t=" + Math.random(),true);
  xmlhttp.send();
}

var xmlhttp;
//Add code for IE8 and 9 for cross site.
var isIE8 = window.XDomainRequest ? true : false;

if(isIE8){
  xmlhttp=new window.XDomainRequest();
}
else if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
}
else{// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}

if(isIE8){
  xmlhttp.onload = function(){
	  if(xmlhttp.responseText == 2){
      displayFacultyNotice();
    }
  }
}
else{
  xmlhttp.onreadystatechange=function(){
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
      if(xmlhttp.responseText == 2){
        displayFacultyNotice();
      }
    }
  }

xmlhttp.open("GET","https://"+ server + "/notice.php?uname=" + username + "&uid=" + uid + "&t=" + Math.random(),true);
xmlhttp.send();
// ]]></script>


<!-- HTML CODE TO BE DISPLAYED IN POP-UP -->
</p>
<div id="copyrightnoticefaculty" style="display: none;">
<div id="copyrightnoticefacultytext" style="margin: 0 auto; z-index: 1002; background-color: #ffffff; height: 100%;">
<p><img src="https://static.dal.ca//etc/designs/dalhousie/clientlibs/global/default/images/logos/dalhousie-logo-black.svg" /></p>
<br />
<h2 style="font-size: 1.5em; font-weight: 125%; margin: 0 auto; line-height: .3 em;">Notice to Faculty and Staff</h2>
<p style="width: 95%; margin: 0 auto; margin-top: .5em;">Materials uploaded into Brightspace are governed by Canadian Copyright Law.</p>
<p style="width: 95%; margin: 0 auto; margin-top: .5em;">By clicking "I confirm" I am asserting:</p>
<ul style="list-style-type: disc; margin-left: 30px; padding: 5px;">
<li>that I am aware of <a href="http://www.dal.ca/dept/copyrightoffice/guidelines/fair-dealing-guidelines.html" target="_blank" style="color: #0000ff;">Dalhousie's copyright guidelines</a>,</li>
<li>that the materials I will upload will be in compliance with Dalhousie's guidelines and policies,</li>
<li>and that if I have any questions about copyright or Dalhousie's guidelines I will contact the Copyright Office at <a href="mailto:Copyright.Office@dal.ca" style="color: #0000ff;">Copyright.Office@dal.ca</a></li>
</ul>
<p style="width: 95%; margin: 0 auto; margin-top: .5em;"></p>
<p style="width: 95%; margin: 0 auto; margin-top: .5em;">Additional information on copyright at Dalhousie can be found on the Copyright Office website at <a href="http://www.dal.ca/dept/copyrightoffice.html" style="color: #0000ff;" target="_blank">http://www.dal.ca/dept/copyrightoffice.html</a></p>
<br />
<h3 style="text-align: left; font-size: 1.5em; font-weight: 110%; margin: 0 auto; line-height: .3 em;"><a href="https://blogs.dal.ca/libraries/2016/02/copyright-messaging-in-brightspace-a-note-for-instructors/" style="color: #0000ff;" target="_blank">Why am I seeing this message?</a></h3>
<br />
<!-- END HTML CODE -->


<script>// <![CDATA[
if(isIE8){
    // type 2 is standing for the copyright confirming; 
    // type 3 is standing for the more information requesting
			  document.write("<a href=\"https://"+server+"/accept.php?uname=" + username + "&type=2&b=ie&t=" + Math.random() + "\" onclick=\"hideNotice();\" style=\"z-index: 1003;\  font: bold 11px Arial; text-decoration: none; background-color: #EEEEEE; color: #333333; padding: 2px 6px 2px 6px; border-top: 1px solid #CCCCCC; border-right: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #CCCCCC;\">I Confirm</a>" +
          "&nbsp;<a href=\"https://"+server+"/accept.php?uname=" + username + "&type=3&b=ie&t=" + Math.random() + "\" onclick=\"hideNotice();alert('An information request has been sent, the copyright office will be in touch shortly.');\" style=\"z-index: 1003;\  font: bold 11px Arial; text-decoration: none; background-color: #EEEEEE; color: #333333; padding: 2px 6px 2px 6px; border-top: 1px solid #CCCCCC; border-right: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #CCCCCC;\">I Would Like More Information</a>");
			}
			else{
			  document.write("<button id='hidebutton' onclick=\"hideNotice('2');\" style=\"z-index: 1003;\">I Confirm</button>" +
                       "&nbsp;<button id='hidebutton' onclick=\"hideNotice('3');alert('An information request has been sent, the copyright office will be in touch shortly.');\" style=\"z-index: 1003;\">I Would Like More Information</button>");
			}
// ]]></script>
</div>
</div>