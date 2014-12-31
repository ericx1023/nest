
//定義全域變數 
canvas               = O('logo')
//需要來自canvas的2d環境來作畫
context              = canvas.getContext('2d')
//設定字體大小
context.font         = 'bold italic 90px Georgia'
//畫線
context.textBaseline = 'top'
//定義圖檔
image                = new Image()
image.src            = 'images/robin.gif'


image.onload = function()
{
  //漸層色 createLinearGradient(起始色x軸, 起始色y軸, 終止色x軸, 終止色y軸)
  gradient = context.createLinearGradient(0, 0, 0, 89)
  //改變漸層addColorStop(0-1指定開始與結束漸層的位置, 結束的顏色)
  gradient.addColorStop(0.33, '#faa')
  gradient.addColorStop(0.66, '#f00')
  

  context.fillStyle = gradient
  context.fillText(  "Sh  ng's Nest", 0, 0)

  //描外邊 strokeText(內容,x,y,maxWidth);
  context.strokeText("Sh  ng's Nest", 0, 0)
  //畫入圖檔
  context.drawImage(image, 105, 32)
}

//判斷傳入的i是否為物件？是的話傳回i, 不是的話取得DOM中的i物件
function O(i) { return typeof i == 'object' ? i : document.getElementById(i) }
//取得i物件的style屬性
function S(i) { return O(i).style                                            }
//取得i物件的類別名稱
function C(i) { return document.getElementsByClassName(i)                    }
