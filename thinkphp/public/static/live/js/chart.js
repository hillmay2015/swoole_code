/**
 * Created by may on 2019/6/19.
 */
var wsUrl = 'ws://127.0.0.1:8816';

var websocket = new WebSocket(wsUrl);

//实例对象onopen属性
websocket.onopen = function (evt) {
    //push(evt.data);
    console.log("8816-conneted success ");
}

//实例化 onmessage

websocket.onmessage = function (evt) {
    //对数据进行判断 逻辑处理
    push(evt.data);
    console.log("8816-ws-sever-return-data is : " + evt.data);
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
    html = '<div class="comment">';
    html += ' <span>' + data.user + '</span>';
    html += '  <span>' + data.content + '</span>';
    html += ' </div>';
    $('#comments').prepend(html);
}