/* myLoc.js */

var watchId = null;
var map = null;
var prevCoords = null;

window.onload = getMyLocation;

//取得位置
function getMyLocation() {
    //檢查瀏覽器是否支援
    if (navigator.geolocation) {
        //呼叫getCurrentPosition方法
        navigator.geolocation.getCurrentPosition(
            displayLocation, 
            displayError,
            {enableHighAccuracy: true, timeout:9000});

        var watchButton = document.getElementById("watch");
        watchButton.onclick = watchLocation;
        var clearWatchButton = document.getElementById("clearWatch");
        clearWatchButton.onclick = clearWatch;
    }
    else {
        //不支援地理定位
        alert("糟糕，瀏覽器並不支援地理定位");
    }
}
//顯示定位 
function displayLocation(position) 
{
    //從position.coords物件中擷取所在資訊 
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;

    var div = document.getElementById("location");
    //從html中取得div
    div.innerHTML = "您的緯度在: " + latitude + ", 經度在: " + longitude +"<br>";
    div.innerHTML += " (精確範圍: " + (position.coords.accuracy/1000) + "公里)";

    showMap(position.coords);

}


function showMap(coords) {
    //利用google maps API 接收經緯度資訊，回傳一個經緯度物件
    var googleLatAndLong = new google.maps.LatLng(coords.latitude, 
                                                  coords.longitude);
    //選項設定，包括縮放，中心，地圖模式..等
    var mapOptions = {
        zoom: 10,
        center: googleLatAndLong,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    //從DOM取得map的div元件 
    var mapDiv = document.getElementById("map");
    //MAP物件建構式，接受元件與選項，回傳一個Map物件
    map = new google.maps.Map(mapDiv, mapOptions);

    var title = "您的位置";
    var content = "您在這裡: " + coords.latitude + ", " +coords.longitude;
    //傳入地圖，googleAPI物件，標題內容...
    addMarker(map, googleLatAndLong, title, content);
}
//增加大頭針標示 
//接受引數包括一份地圖, 經緯度, 標記標題, 內容  
function addMarker(map, latlong, title, content) {
    //建立一個marker物件
    var markerOptions = {
        position: latlong,
        map: map,
        title: title,
        //點選標記時呈現一個資訊視窗
        clickable: true
    };
    //建構一個googleAPI Marker物件，傳給marker
    var marker = new google.maps.Marker(markerOptions);

    //建立資訊視窗 定義選項規格 以Google API新建infoWindow物件
    var infoWindowOptions = {
        content: content, //內容 
        position: latlong //經緯度
    };
    //建立資訊視窗
    var infoWindow = new google.maps.InfoWindow(infoWindowOptions);
    //使用addListener方法增加收聽器
    //傳給收聽器一個函式，會於使用者點標記時被呼叫，在地圖上開啟infoWindow, 
    google.maps.event.addListener(marker, 'click', function() {
        infoWindow.open(map);
    });
}


function displayError(error) {
    var errorTypes = {
        0: "Unknown error",
        1: "Permission denied",
        2: "Position is not available",
        3: "Request timeout"
    };
    var errorMessage = errorTypes[error.code];
    if (error.code == 0 || error.code == 2) {
        errorMessage = errorMessage + " " + error.message;
    }
    var div = document.getElementById("location");
    div.innerHTML = errorMessage;
}

//
// Code to watch the user's location
//
function watchLocation() {
    watchId = navigator.geolocation.watchPosition(
                    displayLocation, 
                    displayError,
                    {enableHighAccuracy: true, timeout:3000});
}

function scrollMapToPosition(coords) {
    var latitude = coords.latitude;
    var longitude = coords.longitude;

    var latlong = new google.maps.LatLng(latitude, longitude);
    map.panTo(latlong);

    // add the new marker
    addMarker(map, latlong, "Your new location", "You moved to: " + 
                                latitude + ", " + longitude);
}

function clearWatch() {
    if (watchId) {
        navigator.geolocation.clearWatch(watchId);
        watchId = null;
    }
}


