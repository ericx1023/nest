
<?php
  //登入的表單 
  require_once 'header.php';
  echo "<div class='main'><h3>請輸入登入資訊：</h3>";
  $error = $user = $pass = "";

  if (isset($_POST['user']))
  {
    //刪除可能的惡意字集
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    
    if ($user == "" || $pass == "")
        $error = "所有欄位都必須填寫<br>";
    else
    {
      $result = queryMySQL("SELECT user,pass FROM members
        WHERE user='$user' AND pass='$pass'");

      if ($result->num_rows == 0)
      {
        $error = "<span class='error'>使用者名稱或密碼無效</span><br><br>";
      }
      else
      {
        //將user, pass 指派至期程變數，只要期程持續動作，可讓專案所有程式存取。
        //給所有已登入的使用者存取網頁內容
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $pass;

        die("您已經登入了，請<a href='members.php?view=$user'>" .
            "按這裡</a>繼續.<br><br>");
      }
    }
  }

  echo <<<_END
    <form method='post' action='login.php'>$error
    <span class='fieldname'>Username</span><input type='text'
      maxlength='16' name='user' value='$user'><br>
      <!-- 將input type 設為password 遮蔽密碼-->
    <span class='fieldname'>Password</span><input type='password'
      maxlength='16' name='pass' value='$pass'>
_END;
?>

    <br>
    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Login'>
    </form><br></div>
  </body>
</html>
