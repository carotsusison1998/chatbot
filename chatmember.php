<?php require_once './inc/header.php'; ?>
<?php
    require_once 'query/member.php';
    require_once 'query/room.php';
    require_once 'query/chat_one_one.php';
    $chat_room = new \room;
    $member = new \member;
    $chat_one = new \chat_one_one;

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
    $get_data = $chat_room->getData();
    $get_list_member = $member->getListMember();
    $getMemberById = $member->getMemberById($_GET['member']);
    $get_list_chat_one = $chat_one->getListChatOneOne($_SESSION['member']['id_member'], $_GET['member']);
    // echo "<pre>";
    // print_r($get_list_chat_one);
    // echo "</pre>";
    if(isset($_GET['member'])){
        echo '<input type="hidden" id="recieve_user_id" value="'.$_GET['member'].'" />';
    }
?>
<div class="container">
    <h4 class="aligncenter pb-3">Phòng Chat Member</h4>
    <div class="chat">
        <div class="row">
            <div class="content-chat" id="content-chat-member">
                <div class="bg">
                    <div class="list">
                        <?php if($get_list_chat_one){ 
                            foreach($get_list_chat_one as $item){
                                $name_member = $member->getMemberById($item['id_member_one']);
                        ?>
                        <?php if($item['id_member_one'] != $_SESSION['member']['id_member']){ ?>
                                <div class="list-msg left">
                                    <div class="item-msg">
                                        <div class="info">
                                            <div class="img">
                                                <img src="./libs/images/ic-men.png" alt="">
                                            </div>
                                            <span class="text-name"><?php echo $name_member[0]['name_member']; ?></span>
                                        </div>
                                        <div class="text-msg">
                                            <p><?php echo $item['message']; ?></p>
                                        </div>
                                    </div>
                                </div>
                        <?php }else{ ?>
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
                        <textarea name="" class="input-text" id="input-text-member"></textarea>
                        <input type="submit" class="btn btn-submit" id="btn-send-member" value="Gửi">
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
                </div>
                <!-- <div class="list-member">
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
                                                <div class="item">
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
                </div> -->
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
        if(data.action == "chat-member"){
            if(data.id == $("#login_user_id").val() || data.id_recieve == $("#login_user_id").val()){
                var html = '';
                if(data.id == <?php echo $_GET['member']; ?>){
                    console.log('do');
                    if(data.from == 'Me'){
                        html += "<div class='list-msg right'><div class='item-msg'><div class='text-msg'><p>"+data.msg+"</p></div></div></div>";
                    }else if(data.from == 'you'){
                        html += "<div class='list-msg left'><div class='item-msg'><div class='info'><div class='img'><img src='./libs/images/ic-men.png' alt=''></div><span class='text-name'>"+data.name+"</span></div><div class='text-msg'><p>"+data.msg+"</p></div></div></div>";
                    }
                    $('#content-chat-member .list').append(html);
                    $('#content-chat-member .list').scrollTop($('#content-chat-member .list')[0].scrollHeight);
                }
                if(data.id_recieve == <?php echo $_GET['member']; ?>){
                    console.log('do 2');
                    if(data.from == 'Me'){
                        html += "<div class='list-msg right'><div class='item-msg'><div class='text-msg'><p>"+data.msg+"</p></div></div></div>";
                    }else if(data.from == 'you'){
                        html += "<div class='list-msg left'><div class='item-msg'><div class='info'><div class='img'><img src='./libs/images/ic-men.png' alt=''></div><span class='text-name'>"+data.name+"</span></div><div class='text-msg'><p>"+data.msg+"</p></div></div></div>";
                    }
                    $('#content-chat-member .list').append(html);
                    $('#content-chat-member .list').scrollTop($('#content-chat-member .list')[0].scrollHeight);
                }

            }
        }
    };
    $("#btn-send-member").click(function(){
        if($("#input-text-member").val()){
            var obj = {
                id: $("#login_user_id").val(),
                id_recieve: $("#recieve_user_id").val(),
                name: $("#login_user_name").val(),
                msg: $("#input-text-member").val(),
                action: "chat-member"
            }
            conn.send(JSON.stringify(obj))
            $("#input-text-member").val("");
        }
    })
    $(document).keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var obj = {
                id: $("#login_user_id").val(),
                id_recieve: $("#recieve_user_id").val(),
                name: $("#login_user_name").val(),
                msg: $("#input-text-member").val(),
                action: "chat-member"
            }
            if($("#input-text-member").val()){
                conn.send(JSON.stringify(obj))
                setTimeout(() => {
                    $("#input-text-member").val("");
                }, 10);
            }
        }
    });
</script>
<?php require_once './inc/footer.php'; ?>