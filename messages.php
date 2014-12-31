<?php 
  require_once 'header.php';

  if (!$loggedin) die();

  if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
  else                      $view = $user;

  //檢查是否有訊息被貼到POST變數'text'
  if (isset($_POST['text']))
  {
    $text = sanitizeString($_POST['text']);

    //如果有訊息
    if ($text != "")
    {
      //儲存pm的值，只是訊息是私人(1)或是公共(0)的
      $pm   = substr(sanitizeString($_POST['pm']),0,1);
      $time = time();
      //插入訊息資料表中
      queryMysql("INSERT INTO messages VALUES(NULL, '$user',
        '$view', '$pm', $time, '$text')");
    }
  }
  //如果有觀看
  if ($view != "")
  {
    if ($view == $user) $name1 = $name2 = "您的";
    else
    {
      $name1 = "<a href='members.php?view=$view'>$view</a> 的";
      $name2 = "$view 的";
    }
    //顯示個人檔案
    echo "<div class='main'><h3>$name1 訊息</h3>";
    showProfile($view);
    
    //顯示輸入訊息的表單
    echo <<<_END
      <form method='post' action='messages.php?view=$view'>
      留下訊息給$name2 留言板:<br>
      <textarea name='text' cols='40' rows='3'></textarea><br>
      公開<input type='radio' name='pm' value='0' checked='checked'>
      私人<input type='radio' name='pm' value='1'>
      <input type='submit' value='留下訊息'></form><br>
_END;
    //如果erase有值，
    if (isset($_GET['erase']))
    {
      //將erase的值消毒，並清除該訊息
      $erase = sanitizeString($_GET['erase']);
      queryMysql("DELETE FROM messages WHERE id=$erase AND recip='$user'");
    }
    //依照時間查詢訊息，並儲存在$query中去問資料庫
    $query  = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC";
    $result = queryMysql($query);
    $num    = $result->num_rows;
    
    for ($j = 0 ; $j < $num ; ++$j)
    { 
      //獲取MYSQLI_ASSOC結果集合
      $row = $result->fetch_array(MYSQLI_ASSOC);

      //設定時區為台北時間
      date_default_timezone_set('Asia/Taipei');
      //如果訊息是公開或作者是本人，或收件者是本人
      if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user)
      {
        echo date('Y年 m月 j日 D g:ia:', $row['time']);
        echo " <a href='messages.php?view=" . $row['auth'] . "'>" . $row['auth']. "</a> ";

        //如果訊息是公開的
        if ($row['pm'] == 0)
          echo "說: &quot;" . $row['message'] . "&quot; ";
        else //若是私人的
          echo "悄悄地說: <span class='whisper'>&quot;" .
            $row['message']. "&quot;</span> ";
        //若收件者是本人    
        if ($row['recip'] == $user)
          echo "[<a href='messages.php?view=$view" .
               "&erase=" . $row['id'] . "'>erase</a>]";

        echo "<br>";
      }
    }
  }
  //若資料庫中沒有訊息傳回值
  if (!$num) echo "<br><span class='info'>還沒有訊息喔～</span><br><br>";

  echo "<br><a class='button' href='messages.php?view=$view'>重新整理</a>";
?>

    </div><br>
  </body>
</html>
