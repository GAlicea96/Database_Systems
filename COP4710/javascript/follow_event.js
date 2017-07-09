
function follow_event(event, user)
{
  if (event.value == '' || user.value == '')
  {
    O('followevent').innerHTML = ''
    return
  }

  params = "event=" + event.value + " user=" + user.value
  request = new ajaxRequest()
  request.open("GET", "follow_event.php?event=" + event +"&user=" + user, true)
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
  request.setRequestHeader("Content-length", params.length)
  request.setRequestHeader("Connection", "close")

  request.onreadystatechange = function()
  {
    if (this.readyState == 4)
      if (this.status == 200)
        if (this.responseText != null)
          O('followevent').innerHTML = this.responseText
  }
  request.send(params)
}


function ajaxRequest()
{
  try { var request = new XMLHttpRequest() }
  catch(e1) {
    try { request = new ActiveXObject("Msxml2.XMLHTTP") }
    catch(e2) {
      try { request = new ActiveXObject("Microsoft.XMLHTTP") }
      catch(e3) {
        request = false
  } } }
  return request
}
