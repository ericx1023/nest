<?php 
  $dbhost  = "localhost";    
  $dbname  = "lifeclub_ShengsNest";   
  $dbuser  = "lifeclub_pc01";   
  $dbpass  = "1234aa";   
  $appname = "交友網站"; 

  //開啟MySQL連線，並選擇資料庫。 
  $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  if ($connection->connect_error) die($connection->connect_error);

  //檢查資料表是否存在，如果不存在則建立
  function createTable($name, $query)
  {
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br>";
  }

  //對MySQL發出一個查詢，如果失敗則輸出錯誤訊息
  function queryMysql($query)
  {
    global $connection;
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    return $result;
  }

  //銷毀一個PHP Session 並且清除其資料來將使用者登出
  function destroySession()
  {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
  }

  //從使用者輸入的資料移除潛在惡意程式與標籤
  function sanitizeString($var)
  {
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
  }

  // 顯示使用者圖片以及『關於我』訊息
  function showProfile($user)
  {
    if (file_exists("images/$user.jpg")){
      echo "<img src='images/$user.jpg' style='float:left' />";
    }

    $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

    if ($result->num_rows)
    {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      echo stripslashes($row['text']) . "<br style='clear:left;'><br>";
    }
  }
?>
