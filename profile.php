<?php 
  require_once 'header.php';

  if (!$loggedin) die();

  echo "<div class='main'><h3>您的個人資料區</h3>";

  $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
    
    //檢查POST變數 text 是否有貼入文字，
  if (isset($_POST['text']))
  {
    //清除潛在惡意字元
    $text = sanitizeString($_POST['text']);
    //連續空白轉換成單一空格
    $text = preg_replace('/\s\s+/', ' ', $text);

    if ($result->num_rows)
         queryMysql("UPDATE profiles SET text='$text' where user='$user'");
    else queryMysql("INSERT INTO profiles VALUES('$user', '$text')");
  }
  else //沒有貼入文字
  {
    //資料庫是否有資料
    if ($result->num_rows)
    {
      //有資料，則獲取資料並貼到textarea中讓使用者編輯
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      //利用stripslashes將引號拿掉
      $text = stripslashes($row['text']);
    }
    else $text = "";
  }

  $text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

  //檢查$_FILES系統變數，是否有上傳的圖片
  if (isset($_FILES['image']['name']))
  {
    //有上傳圖片，根據使用者帳號.jpg來建立$saveto字串變數
    $saveto = "images/$user.jpg";

    //將上傳圖片從檔案暫存位置搬移至$saveto
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto)
    or die ("上傳照片不成功, 錯誤代碼：" . ($_FILES['image']['error']));
    $typeok = TRUE;

    //檢查圖片格式
    switch($_FILES['image']['type'])
    {
      //僅接受gif, jpeg, pjpeg, png類型
      case "image/gif":   $src = imagecreatefromgif($saveto); 
      break;
      case "image/jpeg":  //jpeg + pjpeg
      case "image/pjpeg": $src = imagecreatefromjpeg($saveto); 
      break;
      case "image/png":   $src = imagecreatefrompng($saveto); 
      break;
      //若其他類型，則將typeok旗標設為false
      default:            $typeok = FALSE; 
      break;
    }

    if ($typeok)
    { 
      //將圖片維度存於$w, $h(將陣列值指派給單一變數)
      list($w, $h) = getimagesize($saveto);
      //使用$max來計算新的維度，產生同樣比率的新圖，使維度不超過100px
      $max = 100;
      $tw  = $w;
      $th  = $h;
      //1. 寬 > 高 and 寬 > 100
      if ($w > $h && $max < $w)
      {
        //令寬 = 100, 高等比例縮放]
        $th = $max / $w * $h;
        $tw = $max;
      }
      //2. 高 > 寬 and 高 > 100
      elseif ($h > $w && $max < $h)
      {
        //令高 = 100, 寬等比例縮放
        $tw = $max / $h * $w;
        $th = $max;
      }
      //3. 寬高都小於 100, 令寬高 = 100
      elseif ($max < $w)
      {
        $tw = $th = $max;
      }
      //建立一個長為tw, 寬為th的空白畫布
      $tmp = imagecreatetruecolor($tw, $th);
      //將圖片從$src重新取樣給$tmp
      imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
      //避免取樣造成的模糊，利用imageconvolution銳化圖片
      imageconvolution($tmp, array(array(-1, -1, -1),
        array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
      //將圖片轉成jpeg儲存在$saveto
      imagejpeg($tmp, $saveto);
      //將原始與改變大小的畫布移除
      imagedestroy($tmp);
      imagedestroy($src);
    }
  }
  //呼叫fuction.php的showProfile函式，如果沒有儲存個人檔案則不會顯示任何資料
  showProfile($user);

  //表單中multipart/form-data，允許一次傳送多種資料
  echo <<<_END
    <form method='POST' action='profile.php' enctype='multipart/form-data'>
    <h3>編輯您的個人資料及上傳圖片</h3>
    <textarea name='text' cols='50' rows='3'>$text</textarea><br>
_END;
?>
    <!-- 建立瀏覽按鈕讓使用者選擇上傳圖片 -->
    Image: <input type='file' name='image' size='14' maxlength='32' />
    <input type='submit' value='儲存個人資料'/>
    </form></div><br>
  </body>
</html>
