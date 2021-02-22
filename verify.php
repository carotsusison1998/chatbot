<?php require_once './inc/header.php'; ?>
<?php
    require_once 'query/member.php';
    $msg_error = '';
    $msg_success = '';
    $member = new member;
    if(isset($_GET['code'])){
        $rs = $member->is_valid_code_member($_GET['code']);
        if($rs){
            $result = $member->enable_user_account($_GET['code'], "Enable");
            if($result[0]){
                session_start();
                $_SESSION['member'] = [
                    "id_member" => $result[0]['id_member'],
                    "name_member" => $result[0]['name_member'],
                    "email_member" => $result[0]['email_member'],
                    "profile_member" => $result[0]['profile_member'],
                ];
                $msg_success = "Xác nhận thành công!";
                echo "<script>setTimeout(function(){window.location.href = 'http://localhost/chatbot/chatroom.php'}, 2000);</script>";
            }
        }
    }

?>
<div class="container form-submit">
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
</div>
<?php require_once './inc/footer.php'; ?>