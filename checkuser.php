<?php 
  //需要呼叫sanitizeString與queryMysql，引入function.php
  require_once 'functions.php';


  //user存有值，使用user來查詢資料庫
  if (isset($_POST['user']))
  {
    $user   = sanitizeString($_POST['user']);
    $result = queryMysql("SELECT * FROM members WHERE user='$user'");
    //問帳號是否存在
    if ($result->num_rows)
      //HTML實體&#x2718, &#x2714用在字串前加上打叉或打勾的記號
      echo  "<span class='taken'>&nbsp;&#x2718; " .
            "This username is taken</span>";
    else
      echo "<span class='available'>&nbsp;&#x2714; " .
           "This username is available</span>";
  }
?>
