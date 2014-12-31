<?php 
  require_once 'header.php';

  echo "<br><span class='main'>歡迎來到 $appname,";

  //判斷使用者是否已經登入
  if ($loggedin) echo " Hi! $user, 您已經登入.";
  else           echo ' 請登入或註冊來進入網站';
?>

    </span><br><br>
  </body>
</html>
