<?php 
  require_once 'header.php';

  //checkUser方法 -> 當焦點從表單的帳號欄位移開，onBlur事件會呼叫本函式。
  //將<span#info>設為空字串，清除之前的值。
  //接著對checkUser.php發出請求，回報user是否可以使用
  echo <<<_END
  <script>
    function checkUser(user)
    {
      if (user.value == '')
      {
        O('info').innerHTML = ''
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
              O('info').innerHTML = this.responseText
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
  </script>
  <div class='main'><h3>請輸入登入資訊</h3>
_END;

  //驗證表單
  $error = $user = $pass = "";
  if (isset($_SESSION['user'])) destroySession();

  if (isset($_POST['user']))
  {
    //使用sanitizeString方法來移除潛在惡意字元。
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);

    if ($user == "" || $pass == "")
      $error = "請確認填寫所有欄位<br><br>";
    else
    {
      $result = queryMysql("SELECT * FROM members WHERE user='$user'");
      if ($result->num_rows)
        $error = "帳戶名已存在<br><br>";
      else
      {
        //如果帳號未被使用，插入新的帳號$user與密碼$pass。
        queryMysql("INSERT INTO members VALUES('$user', '$pass')");
        die("<h4>帳號已建立</h4>請登入帳號.<br><br>");
      }
    }
  }

  //建立一輸入帳號密碼的表單
  //其中空的<span#info>為檢查帳號是否可使用 Ajax呼叫目標
  echo <<<_END
    <form method='post' action='signup.php'>$error
    <span class='fieldname'>Username</span>
    <input type='text' maxlength='16' name='user' value='$user'
      onBlur='checkUser(this)'><span id='info'></span><br>
    <span class='fieldname'>Password</span>
    <input type='password' maxlength='16' name='pass'
      value='$pass'><br>
_END;
?>

    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Sign up'>
    </form></div><br>
  </body>
</html>
