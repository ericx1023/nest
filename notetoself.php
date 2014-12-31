<?php 
  require_once 'header.php';
  if (!$loggedin) die();

echo <<<_END
    <link rel="stylesheet" href="css/notetoself.css">
    <script src="js/notetoself.js"></script>
    <h3>便利貼：(本機暫存)<br>
    請輸入文字</h3>
    <form id="note" style="margin:5px">
      <input type='text' id='note_text'>
      <input type='button' id='add_button' value='新增便利貼'>
      <input type="button" id="clear_button" value="清除所有便利貼">
    </form>

    <ul id="stickies">
    </ul>
_END;
?>
    </form></div><br>
  </body>
</html>