<?php require_once './inc/header.php'; ?>
<?php
    session_start();
    require_once 'query/member.php';
    require_once 'query/room.php';
    $chat_room = new \room;
    $member = new \member;

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
    // echo "<pre>";
    // print_r($get_list_member);
    // echo "</pre>";
    if(isset($_GET['member'])){
        echo '<input type="hidden" id="recieve_user_id" value="'.$_GET['member'].'" />';
    }
?>
<div class="container">
    <h4 class="text-center pb-3">Chat Member</h4>
    <div class="chat-room">
        <div class="row">
            <div class="col-md-8">
                <div class="page-content page-container" id="page-content">
                    <div class="row container d-flex justify-content-center">
                        <div class="col-md-12">
                            <div class="card card-bordered">
                                <div class="card-header">
                                    <h4 class="card-title"><strong>Chat</strong></h4> <a class="btn btn-xs btn-secondary" href="#" data-abc="true">Let's Chat App</a>
                                </div>
                                <div class="ps-container ps-theme-default ps-active-y" id="chat-content" style="overflow-y: scroll !important; height:400px !important;">
                                    <?php 
                                        if($get_data){ 
                                        foreach($get_data as $item){
                                            if($item['id_member'] == $_SESSION['member']['id_member']){
                                    ?>
                                            <div class="media media-chat media-chat-reverse">
                                                <img class='avatar' src='https://img.icons8.com/color/36/000000/administrator-male.png' alt='...'>
                                                <div class='media-body'><p><?php echo $item['message']; ?></p></div>
                                            </div>
                                    <?php
                                            }else{
                                            ?>
                                            <div class="media media-chat">
                                                <img class='avatar' src='https://img.icons8.com/color/36/000000/administrator-male.png' alt='...'>
                                                <?php echo $item['name_member']?>
                                                <div class='media-body'><p><?php echo $item['message']; ?></p></div>
                                            </div>
                                            <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="publisher bt-1 border-light">
                                    <img class="avatar avatar-xs" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="...">
                                    <input class="publisher-input" type="text" placeholder="Write something" id="txt-chat-member">
                                    <a class="publisher-btn text-info" href="#" data-abc="true" id="btn-send-member"><i class="fa fa-paper-plane"></i></a> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <?php if($_SESSION['member']){ ?>
                    <input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $_SESSION['member']['id_member']; ?>" />
                    <input type="hidden" name="login_user_name" id="login_user_name" value="<?php echo $_SESSION['member']['name_member']; ?>" />
                    <div class="mt-3 mb-3 text-center">
                        <img src="./libs/images/1613618355.png" width="150" class="img-fluid rounded-circle img-thumbnail" />
                        <h3 class="mt-2"><?php echo $_SESSION['member']['name_member']; ?></h3>
                        <a href="profile.php" class="btn btn-secondary mt-2 mb-2">Edit</a>
                        <form method="POST" action="chatroom.php">
                            <input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $_SESSION['member']['id_member']; ?>" />
                            <input type="submit" class="btn btn-primary mt-2 mb-2" name="logout" id="logout" value="Logout" />
                        </form>
                    </div>
                <?php } ?>
                <div class="card mt-3">
					<div class="card-header">User List</div>
					<div class="card-body" id="user_list">
						<div class="list-group list-group-flush">
                            <a class="list-group-item list-group-item-action" href="./chatroom.php">Room</a>
						<?php
						if(count($get_list_member) > 0)
						{
							foreach($get_list_member as $key => $user)
							{
								$icon = '<i class="fa fa-circle text-danger"></i>';

								if($user['login_status_member'] == 'Login')
								{
									$icon = '<i class="fa fa-circle text-success"></i>';
								}

								if($user['id_member'] != $_SESSION['member']['id_member'])
								{
									echo '
									<a class="list-group-item list-group-item-action">
										<img src="./libs/images/1613618355.png" class="img-fluid rounded-circle img-thumbnail" width="50" />
										<span class="ml-1"><strong>'.$user["name_member"].'</strong></span>
										<span class="mt-2 float-right">'.$icon.'</span>
									</a>
									';
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
            console.log("chat member", data);
        }
    };
    $("#btn-send-member").click(function(){
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

    // $("#btn-send").click(function(){
    //     if($("#txt-chat").val()){
    //         var obj = {
    //             id: $("#login_user_id").val(),
    //             name: $("#login_user_name").val(),
    //             msg: $("#txt-chat").val(),
    //             action: "chat-room"
    //         }
    //         conn.send(JSON.stringify(obj))
    //     }
    // })
    // $(document).keypress(function(event){
    //     var keycode = (event.keyCode ? event.keyCode : event.which);
    //     if (keycode == '13') {
    //         var obj = {
    //             id: $("#login_user_id").val(),
    //             name: $("#login_user_name").val(),
    //             msg: $("#txt-chat").val(),
    //             action: "chat-room"
    //         }
    //         if($("#txt-chat").val()){
    //             conn.send(JSON.stringify(obj))
    //         }
    //     }
    // });
</script>
<?php require_once './inc/footer.php'; ?>