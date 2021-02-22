<?php require_once './inc/header.php'; ?>
<?php
    require_once 'query/member.php';
    session_start();
    $msg_error = '';
    $msg_success = '';
    if(isset($_SESSION['member'])){
        header('location:chatroom.php');
    }
    if(isset($_POST['login'])){
        $member = new member;
        $check_member = $member->login_member(
            $_POST['email_member'],
            $_POST['password_member'],
        );
        if($check_member){
            $_SESSION['member'] = [
                "id_member" => $check_member[0]['id_member'],
                "name_member" => $check_member[0]['name_member'],
                "email_member" => $check_member[0]['email_member'],
                "profile_member" => $check_member[0]['profile_member'],
            ];
            $msg_success = "Đăng nhập thành công!";
            echo "<script>setTimeout(function(){window.location.href = 'http://localhost/chatbot/chatroom.php'}, 2000);</script>";
        }else{
            $msg_error = "Sai tài khoản hoặc mật khẩu! vui lòng thử lại";
        }
    }
?>
<div class="container form-submit">
    <h4 class="text-center pb-3">Đăng Nhập Tài Khoản</h4>
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
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" required class="form-control" name="email_member" placeholder="Enter email" />
        </div>
        <div class="form-group">
            <label for="pwd">Mật khẩu:</label>
            <input type="password" required class="form-control" minlength="6" maxlength="50" name="password_member" placeholder="Enter password" />
        </div>
        <div class="form-group form-check">
        </div>
        <button type="submit" class="send btn btn-primary" name="login">Đăng Nhập</button>
        <a href="./" class="link btn btn-info">Trở Về</a>
    </form>
</div>
<?php require_once './inc/footer.php'; ?>