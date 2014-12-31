<?php 
  //詢問friends資料表，顯示該使用者所有『互為朋友』，跟隨者，與他跟隨的對象。
  require_once 'header.php';

  if (!$loggedin) die();
  //如果view有值，表示使用者想要檢視他人資料
  //清除可能輸入的惡意字元
  if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
  else                      $view = $user;
  
  //兩種模式，1. 顯示個人資料, 2. 列出他人資料
  //1. 顯示個人資料
  if ($view == $user)
  {
    $name1 = $name2 = "您的";
    $name3 =          "您的";
  }
  //2. 列出他人資料
  else
  {
    $name1 = "<a href='members.php?view=$view'>$view</a> 的";
    $name2 = "$view ";
    $name3 = "$view 正";
  }

  echo "<div class='main'>";

  // 顯示使用者的個人資料
   showProfile($view);

  //定義跟隨者陣列
  $followers = array();
  //定義被跟隨者陣列
  $following = array();

  //問資料庫跟隨者是誰
  $result = queryMysql("SELECT * FROM friends WHERE user='$view'");
  $num    = $result->num_rows;

  //將所有跟隨者存入followers陣列
  for ($j = 0 ; $j < $num ; ++$j)
  {
    $row           = $result->fetch_array(MYSQLI_ASSOC);
    $followers[$j] = $row['friend'];
  }
  //問資料庫被跟隨者是誰
  $result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
  $num    = $result->num_rows;

  //將所有被跟隨者存入following陣列
  for ($j = 0 ; $j < $num ; ++$j)
  {
      $row           = $result->fetch_array(MYSQLI_ASSOC);
      $following[$j] = $row['user'];
  }
  //取出所有共存於兩個陣列 == 同時為跟隨與被跟隨的會員
  $mutual    = array_intersect($followers, $following);
  //利用array_diff 計算出兩陣列的差異，排除掉follower中互為朋友的會員 
  $followers = array_diff($followers, $mutual);
  //利用array_diff 計算出兩陣列的差異，排除掉following中互為朋友的會員
  $following = array_diff($following, $mutual);

  //設立旗標friend值，初始化設為沒有
  $friends   = FALSE;

  //回傳元素數目，$mutual大於零時 = 陣列中有朋友時，則執行相關程式
  if (sizeof($mutual))
  {
    echo "<span class='subhead'>$name2 互為朋友</span><ul>";
    foreach($mutual as $friend)
      echo "<li><a href='members.php?view=$friend'>$friend</a>";
    echo "</ul>";
    $friends = TRUE;
  }

  //回傳元素數目，$followers大於零時 = 陣列中有朋友時，則執行相關程式
  if (sizeof($followers))
  {
    echo "<span class='subhead'>$name2 被追蹤</span><ul>";
    foreach($followers as $friend)
      echo "<li><a href='members.php?view=$friend'>$friend</a>";
    echo "</ul>";
    $friends = TRUE;
  }

  //回傳元素數目，$following大於零時 = 陣列中有朋友時，則執行相關程式
  if (sizeof($following))
  {
    echo "<span class='subhead'>$name3 追蹤</span><ul>";
    foreach($following as $friend)
      echo "<li><a href='members.php?view=$friend'>$friend</a>";
    echo "</ul>";
    $friends = TRUE;
  }
  //若陣列中沒有朋友，列印出訊息
  if (!$friends) echo "<br>還沒加朋友喔～！<br><br>";

  echo "<a class='button' href='messages.php?view=$view'>" .
       "看 $name2 訊息</a>";
?>

    </div><br>
  </body>
</html>
