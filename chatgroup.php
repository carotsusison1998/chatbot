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
    $checkIssetInGroup = $group_chat->checkIssetInGroup($_GET['id-group'], $_SESSION['member']['id_member']);
    if(!$checkIssetInGroup || count($checkIssetInGroup) < 0){
        header('location:chatroom.php');
    }
    $get_data = $chat_room->getData();
    $get_list_member = $member->getListMember();
    $getMessageInGroup = $group_chat->getMessageInGroup($_GET['id-group']);
    $getMemberInGroup = $group_chat->getMemberInGroup($_GET['id-group']);
    $getListMemberInvite = $group_chat->getListMemberInvite($_GET['id-group']);

    if(isset($_POST['addMember'])){
        $addMemberInGroup = $group_chat->addMemberInGroup($_POST['id_member'], $_POST['name_member'], $_GET['id-group']);
        if($addMemberInGroup){
            $msg_success = "thêm thành viên thành công";
            echo "<script>setTimeout(function(){window.location.href = 'http://localhost/chatbot/chatgroup.php?id-group=".$_GET['id-group']."'}, 3500);</script>";
        }
    }
    // echo "<pre>";
    // print_r($getListMemberInvite);
    // echo "</pre>";
?>

<div class="container">
    <h4 class="aligncenter pb-3">Phòng Chat Group</h4>
    <?php
        if($msg_error != '')
        {
            echo '
            <div class="alert alert-warning alert-dismissible">
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
                            if($getMessageInGroup){ 
                            foreach($getMessageInGroup as $item){
                                $name_member = $member->getMemberById($item['id_member']);
                                if($item['id_member'] != $_SESSION['member']['id_member']){
                        ?>
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
                        <textarea name="" class="input-text" id="input-text-group"></textarea>
                        <input type="submit" class="btn btn-submit" id="btn-submit-group" value="Gửi">
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
                <!-- Button trigger modal -->
                <br>
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Mời thành viên</button>
                <div class="list-member">
                    <h4 class="aligncenter title-list">danh sách thành viên trong group</h4>
                    <div class="list-item">
                        <?php
                            if(count($getMemberInGroup) > 0){
                                foreach($getMemberInGroup as $item){
                                    $name_member = $member->getMemberById($item['id_member']);
                                    if($name_member[0]['login_status_member'] == "Login"){
                                        $status = "<span class='status'></span>";
                                    }else{
                                        $status = "<span class='status red'></span>";
                                    }
                                    if($_SESSION['member']['id_member'] != $name_member[0]['id_member']){
                        ?>
                                            <a href="./chatmember.php?member=<?php echo $item['id_member'];?>">
                                                <div class="item member-<?php echo $item['id_member'];?>">
                                                    <img src="./libs/images/ic-women.png" alt="">
                                                    <div class="item-name">
                                                        <span class="name"><?php echo $name_member[0]['name_member']; ?></span>
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
            </div>
        </div>
    </div>
</div>
<!-- modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        <?php if($getListMemberInvite && count($getListMemberInvite) > 0){ 
                foreach($getListMemberInvite as $item){    
        ?>  
                    <div class="item member-<?php echo $item['id_member'];?>">
                        <form action="./chatgroup.php?id-group=<?php echo $_GET['id-group']; ?>" method="POST">
                            <div class="item-name item-member">
                                <span class="name"><?php echo $item['name_member']; ?></span>
                                <input type="hidden" value="<?php echo $item['id_member'];?>" name="id_member">
                                <input type="hidden" value="<?php echo $item['name_member'];?>" name="name_member">
                                <input type="submit" value="mời" name="addMember">
                            </div>
                        </form>
                    </div>
        <?php
                }
            }
             ?>
      </div>
      <div class="modal-footer">
            
      </div>
    </div>
  </div>
</div>
<!-- modal -->
<script src="./libs/jquery/jquery.js"></script>
<script>
    var conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };
    // $('#content-chat-room .list').scrollTop($('#content-chat-room .list')[0].scrollHeight);
    conn.onmessage = function(e) {
        var data = JSON.parse(e.data);
        if(data.action == "chat-group" && data.id_group == <?php echo $_GET['id-group']; ?>){
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

    $("#btn-submit-group").click(function(){
        if($("#input-text-group").val()){
            var obj = {
                id: $("#login_user_id").val(),
                id_group: <?php echo $_GET['id-group']?>,
                name: $("#login_user_name").val(),
                msg: $("#input-text-group").val(),
                action: "chat-group"
            }
            conn.send(JSON.stringify(obj))
            setTimeout(() => {
                $("#input-text-group").val("");
            }, 10);
        }
    })
    $(document).keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var obj = {
                id: $("#login_user_id").val(),
                id_group: <?php echo $_GET['id-group']?>,
                name: $("#login_user_name").val(),
                msg: $("#input-text-group").val(),
                action: "chat-group"
            }
            if($("#input-text-group").val()){
                conn.send(JSON.stringify(obj))
                setTimeout(() => {
                    $("#input-text-group").val("");
                }, 10);
            }
        }
    });
</script>
<?php require_once './inc/footer.php'; ?>