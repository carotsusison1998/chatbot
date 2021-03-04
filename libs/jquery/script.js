var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    var data = JSON.parse(e.data);
    if(data.action == "chat-room"){
        if(data.from == 'Me'){
            row_class = 'media media-chat media-chat-reverse';
            img = "";
        }else if(data.from == 'you'){
            row_class = 'media media-chat';
            img = "<img class='avatar' src='https://img.icons8.com/color/36/000000/administrator-male.png' alt='...'>"+data.name;
        }
        var html_data = "<div class='"+row_class+"'>"+img+"<div class='media-body'><p>"+data.msg+"</p></div></div>";
        $('#chat-content').append(html_data);
        $("#txt-chat").val("");
        $('#chat-content').scrollTop($('#chat-content')[0].scrollHeight);
    }
};

$("#btn-send").click(function(){
    if($("#txt-chat").val()){
        var obj = {
            id: $("#login_user_id").val(),
            name: $("#login_user_name").val(),
            msg: $("#txt-chat").val(),
            action: "chat-room"
        }
        conn.send(JSON.stringify(obj))
    }
})
$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
        var obj = {
            id: $("#login_user_id").val(),
            name: $("#login_user_name").val(),
            msg: $("#txt-chat").val(),
            action: "chat-room"
        }
        if($("#txt-chat").val()){
            conn.send(JSON.stringify(obj))
        }
    }
});

// 
$("#btn-send-member").click(function(){
    console.log("aaaaa");
    if($("#txt-chat-member").val()){
        var obj = {
            id: $("#login_user_id").val(),
            id_recieve: $("#recieve_user_id").val(),
            name: $("#login_user_name").val(),
            msg: $("#txt-chat-member").val(),
            action: "chat-member"
        }
        conn.send(JSON.stringify(obj))
    }
})


