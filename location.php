<?php 
  require_once 'header.php';

  if (!$loggedin) die();
  
echo <<<_END
    <script src='js/location.js'></script>
    <link rel='stylesheet' href='css/location.css'>
    <h3>確認位置中...</h3>
    <div id="location">經緯度在此：
    </div>
    <div id="map">
    </div>
_END;
?>
    </form></div><br>
  </body>
</html>