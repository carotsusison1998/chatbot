<?php require_once './inc/header.php'; ?>
<?php
    require_once 'query/member.php';
    require_once 'query/room.php';
    require_once 'query/group_chat.php';
    $chat_room = new \room;
    $member = new \member;
    $group_chat = new \group_chat;
    $msg_success = '';
    $msg_error = '';
    if(!$_SESSION['member']){
        header('location:login.php');
    }
    if(isset($_POST['logout'])){
        $logout = $member->logoutMember($_POST['login_user_id']);
        if($logout){
            unset($_SESSION['member']);
            header('location:login.php');
        }
    }
    if(isset($_POST['group_chat'])){
        
        $group_chat->setGroupChat($_POST['login_user_id'], "Hello", "open");
        $saveGroupChat = $group_chat->saveGroupChat();
        if($saveGroupChat){
            $msg_success = "tạo group thành công";
        }
    }
    
    $get_data = $chat_room->getData();
    $get_list_member = $member->getListMember();
    $getGroupChatById = $group_chat->getGroupChatById($_SESSION['member']['id_member']);
    // echo "<pre>";
    // print_r($getGroupChatById);
    // echo "</pre>";
?>
<div class="container">
    <h4 class="aligncenter pb-3">Phòng Chat Server</h4>
    <?php
        if($msg_error != '')
        {
            echo '
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                '.$msg_error.'
            </div>
            ';
        }

        if($msg_success != '')
        {
            echo '
            <div class="alert alert-success">
            '.$msg_success.'
            </div>
            ';
        }
    ?>
    <div class="chat">
        <div class="row">
            <div class="content-chat" id="content-chat-room">
                <div class="bg">
                    <div class="list">
                        <?php 
                            if($get_data){ 
                            foreach($get_data as $item){
                                if($item['id_member'] != $_SESSION['member']['id_member']){
                        ?>
                                <div class="list-msg left">
                                    <div class="item-msg">
                                        <div class="info">
                                            <div class="img">
                                                <img src="./libs/images/ic-men.png" alt="">
                                            </div>
                                            <span class="text-name"><?php echo $item['name_member']?></span>
                                        </div>
                                        <div class="text-msg">
                                            <p><?php echo $item['message']; ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                        <?php
                                }else{
                                ?>
                                <div class="list-msg right">
                                    <div class="item-msg">
                                        <div class="text-msg">
                                            <p><?php echo $item['message']; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="text-send">
                        <textarea name="" class="input-text" id="input-text-server"></textarea>
                        <input type="submit" class="btn btn-submit" id="btn-submit-server" value="Gửi">
                    </div>
                </div>
            </div>
            <div class="member-chat">
                <div class="img">
                    <img src="./libs/images/ic-women.png" alt="">
                    <h4 class="aligncenter name"><?php echo $_SESSION['member']['name_member']; ?></h4>
                </div>
                <div class="action">
                    <form method="POST" action="chatroom.php">
                        <input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $_SESSION['member']['id_member']; ?>" />
                        <input type="hidden" name="login_user_name" id="login_user_name" value="<?php echo $_SESSION['member']['name_member']; ?>" />
                        <input type="submit" class="btn btn-primary mt-2 mb-2 logout" name="logout" id="logout" value="Logout" />
                    </form>
                    <form method="POST" action="chatroom.php">
                        <input type="hidden" name="login_user_id" id="login_user_id1" value="<?php echo $_SESSION['member']['id_member']; ?>" />
                        <input type="hidden" name="login_user_name" id="login_user_name1" value="<?php echo $_SESSION['member']['name_member']; ?>" />
                        <input type="submit" class="btn btn-primary mt-2 mb-2 group_chat" name="group_chat" id="group_chat" value="Tạo Group" />
                    </form>
                </div>
                <div class="list-member">
                    <h4 class="aligncenter title-list">danh sách thành viên</h4>
                    <div class="list-item">
                        <?php
                            if(count($get_list_member) > 0){
                                foreach($get_list_member as $item){
                                    if($item['login_status_member'] == "Login"){
                                        $status = "<span class='status'></span>";
                                    }else{
                                        $status = "<span class='status red'></span>";
                                    }
                                    if($_SESSION['member']['id_member'] != $item['id_member']){
                        ?>
                                            <a href="./chatmember.php?member=<?php echo $item['id_member'];?>">
                                                <div class="item member-<?php echo $item['id_member'];?>">
                                                    <img src="./libs/images/ic-women.png" alt="">
                                                    <div class="item-name">
                                                        <span class="name"><?php echo $item['name_member']; ?></span>
                                                        <?php echo $status; ?>
                                                    </div>
                                                </div>
                                            </a>
                        <?php 
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="list-member">
                    <h4 class="aligncenter title-list">danh sách group chat</h4>
                    <div class="list-item">
                        <?php
                            if(count($getGroupChatById) > 0){
                                foreach($getGroupChatById as $item){
                                    
                        ?>
                                <a href="./chatgroup.php?id-group=<?php echo $item['id_group']?>">
                                    <div class="item">
                                        <img src="./libs/images/ic-women.png" alt="">
                                        <div class="item-name">
                                            <span class="name"><?php echo $item['name_group']; ?></span>
                                            <?php echo $item['rule_group']; ?>
                                        </div>
                                    </div>
                                </a>
                        <?php 
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./libs/jquery/jquery.js"></script>
<script>
    var conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };
    conn.onmessage = function(e) {
        var data = JSON.parse(e.data);
        if($(".member-"+data.id).length > 0 && $("#login_user_id").val() == data.id_recieve){
            if($(".member-"+data.id+" .count-msg").length > 0){
                var i = parseInt($(".member-"+data.id+" .count-msg").html()) + 1;
                $(".member-"+data.id+" .count-msg").remove();
                $(".member-"+data.id).append("<span class='count-msg'>"+i+"</span>");
            }else{
                $(".member-"+data.id).append("<span class='count-msg'>1</span>");
                $(".member-"+data.id).css("color", "green");
            }
        }
        // console.log('data', data);
        if(data.action == "chat-room"){
            var html =  '';
            if(data.from == 'Me'){
                html += "<div class='list-msg right'><div class='item-msg'><div class='text-msg'><p>"+data.msg+"</p></div></div></div>";
            }else if(data.from == 'you'){
                html += "<div class='list-msg left'><div class='item-msg'><div class='info'><div class='img'><img src='./libs/images/ic-men.png' alt=''></div><span class='text-name'>"+data.name+"</span></div><div class='text-msg'><p>"+data.msg+"</p></div></div></div>";
            }
            $('#content-chat-room .list').append(html);
            $('#content-chat-room .list').scrollTop($('#content-chat-room .list')[0].scrollHeight);
        }
    };

    $("#btn-submit-server").click(function(){
        if($("#input-text-server").val()){
            var obj = {
                id: $("#login_user_id").val(),
                name: $("#login_user_name").val(),
                msg: $("#input-text-server").val(),
                action: "chat-room"
            }
            conn.send(JSON.stringify(obj))
            setTimeout(() => {
                $("#input-text-server").val("");
            }, 10);
        }
    })
    $(document).keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var obj = {
                id: $("#login_user_id").val(),
                name: $("#login_user_name").val(),
                msg: $("#input-text-server").val(),
                action: "chat-room"
            }
            if($("#input-text-server").val()){
                conn.send(JSON.stringify(obj))
                setTimeout(() => {
                    $("#input-text-server").val("");
                }, 10);
            }
        }
    });
</script>
<?php require_once './inc/footer.php'; ?>