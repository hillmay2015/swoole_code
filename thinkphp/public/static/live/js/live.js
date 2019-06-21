/**
 * Created by may on 2019/6/19.
 */
var wsUrl = 'ws://127.0.0.1:8815';

var websocket = new WebSocket(wsUrl);

//实例对象onopen属性
websocket.onopen = function (evt) {
    console.log("conneted success ");
}

//实例化 onmessage

websocket.onmessage = function (evt) {
    //对数据进行判断 逻辑处理
    push(evt.data);
    console.log("ws-sever-return-data is : " + evt.data);
}

//onclose
websocket.onclose = function (evt) {
    console.log("close");
}
//onerror
websocket.onerror = function (evt, e) {
    console.log("error:" + evt.data);
}

function push(data) {
    data = JSON.parse(data);
    html = '<div class="frame">';
    html += '<h3 class="frame-header">';
    html += '<i class="icon iconfont icon-shijian"></i>第' + data.type + '节 01：30 </h3>';//1:30时间可以在表单中提交
    html += '<div class="frame-item">';
    html += '<span class="frame-dot"></span>';
    html += '<div class="frame-item-author">';
    if (data.logo) {
        html += '<img src="' + data.logo + '" width="20px" height="20px" /> ';
    }
    html += data.title;
    html += '</div>';
    html += '<p>' + data.content + '</p>';
    if (data.image) {
        html += '<p> <img src="' + data.image + '" width="40%" /> </p>';//图片
    }
    html += '</div>';
    html += '</div>';

    $('#match-result').prepend(html);
}