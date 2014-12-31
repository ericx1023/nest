<?php 
  require_once 'header.php';

  if (!$loggedin) die();

  echo "<div class='main'>";

  //測試GET變數view，如果存在，代表使用者想要檢視他人檔案。
  if (isset($_GET['view']))
  {
    $view = sanitizeString($_GET['view']);

    //兩種模式，1. 顯示個人資料, 2. 列出他人資料
    if ($view == $user) $name = "您的";
    else                $name = "$view";
    
    echo "<h3>$name 個人資料</h3>";
    //使用showProfile來檢視他人檔案
    showProfile($view);
    echo "<a class='button' href='messages.php?view=$view'>" .
         "觀看 $name 留言</a><br><br>";
    die("</div></body></html>");
  }

  //如果add或remove有存值，表示使用者有加好友或移除的動作
  if (isset($_GET['add']))
  { 
    //add有存值
    $add = sanitizeString($_GET['add']);

    //在SQL friends資料表查看帳戶是否為朋友
    $result = queryMysql("SELECT * FROM friends WHERE user='$add' AND friend='$user'");
    
    //非朋友
    if (!$result->num_rows)
      //在朋友資料表中增加朋友
      queryMysql("INSERT INTO friends VALUES ('$add', '$user')");
  }
  //如果remove有存值
  elseif (isset($_GET['remove']))
  {
    $remove = sanitizeString($_GET['remove']);
    //在朋友資料表中移除使用者
    queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
  }

  //列出所有帳號，置於$num中
  $result = queryMysql("SELECT user FROM members ORDER BY user");
  $num    = $result->num_rows;

  echo "<h3>其他會員</h3><ul>";

  //迭代每一個成員，抓取其資料
  for ($j = 0 ; $j < $num ; ++$j)
  {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['user'] == $user) continue;
    
    echo "<li><a href='members.php?view=" .
      $row['user'] . "'>" . $row['user'] . "</a>";
    $follow = "follow";

    //在friend資料表中查看是否被其他人跟隨或跟隨其他使用者
    $result1 = queryMysql("SELECT * FROM friends WHERE
      user='" . $row['user'] . "' AND friend='$user'");
    //使用者跟隨其他朋友時，$t1有值
    $t1      = $result1->num_rows;
    $result1 = queryMysql("SELECT * FROM friends WHERE
      user='$user' AND friend='" . $row['user'] . "'");
    //其他朋友跟隨使用者時，$t2有值
    $t2      = $result1->num_rows;

    //互為跟隨的使用者，歸類為『互為朋友』
    if (($t1 + $t2) > 1) echo " &harr; 與您互為朋友"; //雙向箭頭
    elseif ($t1)         echo " &larr; 您正在追蹤"; //向左箭頭
    elseif ($t2)       { echo " &rarr; 正在追蹤您";  //向右箭頭
      $follow = "recip"; }
    
    //如果使用者並未加他人好友時，提供連結來增加或移除好友。
    if (!$t1) echo " [<a href='members.php?add="   .$row['user'] . "'>追蹤</a>]";
    else      echo " [<a href='members.php?remove=".$row['user'] . "'>取消追蹤</a>]";
  }
?>

    </ul></div>
  </body>
</html>
