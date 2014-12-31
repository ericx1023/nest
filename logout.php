<?php 
  require_once 'header.php';

  if (isset($_SESSION['user']))
  {
    destroySession();
    echo "<div class='main'>您已經登出了. 請 " .
         "<a href='index.php'>按此</a> 重新整理頁面.";
  }
  else echo "<div class='main'><br>" .
            "您尚未登入，所以不能登出...";
?>

    <br><br></div>
  </body>
</html>
