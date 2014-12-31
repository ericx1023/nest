<?php 
  
  //每個頁面都會存取同一組值，因此放在header
  //設定一個期程讓不同的php檔案儲存，
  session_start();

  echo "<!DOCTYPE html>\n<html><head>";

  //此檔案包含了function.php
  require_once 'functions.php';


  $userstr = ' (Guest)';
  //檢查期程變數user，如果已被賦值，代表已登入，將loggedin設為true
  if (isset($_SESSION['user']))
  {
    $user     = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr  = " (您是$user)";
  }

  else $loggedin = FALSE;
    
  //登入之後，將帳號加上()放在標題與主標題之後。
  echo "<title>$appname$userstr</title><link rel='stylesheet'"  .
       "href='css/styles.css' type='text/css'>"                     .
       "<meta charset='UTF-8'>"                                 .

       "</head><body><center><canvas id='logo' width='624' "    .
       "height='96'>$appname</canvas></center>"             .
       "<div class='appname'>$appname$userstr</div>"            .
       "<script src='js/javascript.js'></script>"                  .
       "<script src='http://maps.google.com/maps/api/js?sensor=true'></script>";


  //針對已登入的使用者，提供所有功能
  if ($loggedin)
  {
    echo "<br ><ul class='menu'>" .
         "<li><a href='members.php?view=$user'>首頁</a></li>" .
         "<li><a href='members.php'>成員</a></li>"         .
         "<li><a href='friends.php'>朋友</a></li>"         .
         "<li><a href='messages.php'>訊息</a></li>"       .
         "<li><a href='profile.php'>編輯我的資料</a></li>"    .
         "<li><a href='location.php'>我的地圖</a></li>"    .
         "<li><a href='notetoself.php'>記事本</a></li>"    .
         "<li><a href='logout.php'>登出</a></li></ul><br>";
  }
  else
  {
    //未登入的只提供Home, Signup, Log in 的選項
    echo ("<br><ul class='menu'>" .
          "<li><a href='index.php'>首頁</a></li>"                .
          "<li><a href='signup.php'>註冊</a></li>"            .
          "<li><a href='login.php'>登入</a></li></ul><br>"     .
          "<span class='info'>&#8658; 必須要登入才能" .
          "瀏覽此頁面.</span><br><br>");
  }
?>
