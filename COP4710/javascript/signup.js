function checkUser(user)
{
  if (user.value == '')
  {
    O('infouser').innerHTML = ''
    return
  }

  params  = "user=" + user.value
  request = new ajaxRequest()
  request.open("POST", "checkuser.php", true)
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
  request.setRequestHeader("Content-length", params.length)
  request.setRequestHeader("Connection", "close")

  request.onreadystatechange = function()
  {
    if (this.readyState == 4)
      if (this.status == 200)
        if (this.responseText != null)
          O('infouser').innerHTML = this.responseText
  }
  request.send(params)
}

function checkRso(name)
{
  if (name.value == '')
  {
    O('infoname').innerHTML = ''
    return
  }

  params  = "name=" + name.value
  request = new ajaxRequest()
  request.open("POST", "checkRso.php", true)
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
  request.setRequestHeader("Content-length", params.length)
  request.setRequestHeader("Connection", "close")

  request.onreadystatechange = function()
  {
    if (this.readyState == 4)
      if (this.status == 200)
        if (this.responseText != null)
          O('infoname').innerHTML = this.responseText
  }
  request.send(params)
}

function checkEmail(email)
{
  if (email.value == '')
  {
    O('infoemail').innerHTML = ''
    return
  }

  params  = "email=" + email.value
  request = new ajaxRequest()
  request.open("POST", "checkemail.php", true)
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
  request.setRequestHeader("Content-length", params.length)
  request.setRequestHeader("Connection", "close")

  request.onreadystatechange = function()
  {
    if (this.readyState == 4)
      if (this.status == 200)
        if (this.responseText != null)
          O('infoemail').innerHTML = this.responseText
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
