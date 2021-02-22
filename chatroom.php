<?php require_once './inc/header.php'; ?>
<?php
    session_start();
    if(!$_SESSION['member']){
        header('location:login.php');
    }
    require_once 'query/member.php';
?>
<div class="container">
    <h4 class="text-center pb-3">Chat Room</h4>
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
                                    
                                </div>
                                <div class="publisher bt-1 border-light">
                                    <img class="avatar avatar-xs" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="...">
                                    <input class="publisher-input" type="text" placeholder="Write something" id="txt-chat">
                                    <button id="btn-send">Gửi</button>
                                    <span class="publisher-btn file-group">
                                    <i class="fa fa-paperclip file-browser"></i> 
                                    <input type="file"> </span>
                                    <a class="publisher-btn" href="#" data-abc="true"><i class="fa fa-smile"></i></a>
                                    <a class="publisher-btn text-info" href="#" data-abc="true"><i class="fa fa-paper-plane"></i></a> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <?php if($_SESSION['member']){ ?>
                    <input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $_SESSION['member']['id_member']; ?>" />
                    <div class="mt-3 mb-3 text-center">
                        <img src="./libs/images/1613618355.png" width="150" class="img-fluid rounded-circle img-thumbnail" />
                        <h3 class="mt-2"><?php echo $_SESSION['member']['name_member']; ?></h3>
                        <a href="profile.php" class="btn btn-secondary mt-2 mb-2">Edit</a>
                        <input type="button" class="btn btn-primary mt-2 mb-2" name="logout" id="logout" value="Logout" />
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php require_once './inc/footer.php'; ?>