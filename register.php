<?php require_once './inc/header.php'; ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

?>

<?php
    require_once 'query/member.php';
    $msg_error = '';
    $msg_success = '';
    if(isset($_POST['register'])){
        $member = new member;
        $set_member = $member->setMember(
            $_POST['name_member'],
            $_POST['email_member'],
            $_POST['password_member']
        );
        if($set_member){
            $msg_error = "email đã tồn tại! vui lòng nhập email khác";
        }else{
            $rs = $member->getMember();
            if($member->save_member()){
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com"; 
                $mail->SMTPAuth = true;  
                $mail->SMTPSecure = "tls"; 
                $mail->Port = "587";
                $mail->Username = "tnly.16.06.1998@gmail.com";
                $mail->Password = "Lynhi16062505";
                // Recipients
                $mail->CharSet = 'UTF-8';
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Registration Verification for Chat Application Demo';
                $mail->setFrom("tnly.16.06.1998@gmail.com", "Chat Application"); // information person send mail (address, name)
                $mail->Body = '
                <p>Thank you for registering for Chat Application Demo.</p>
                    <p>This is a verification email, please click the link to verify your email address.</p>
                    <p><a href="http://localhost/chatbot/verify.php?code='.$rs['verification_code_member'].'">Click to Verify</a></p>
                    <p>Thank you...</p>
                ';
                $mail->addAddress($rs['email_member']); // information person receive mail (address)
                $mail->send();
                $msg_success = "Vui lòng xác thực email của bạn";
            }else{
                $msg_error = "Đăng ký thất bại! vui lòng thử lại";
            }
        }
    }
?>
<div class="container form-submit">
    <h4 class="text-center pb-3">Đăng Ký Tài Khoản</h4>
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
    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="email">Họ và Tên:</label>
            <input type="text" required class="form-control" minlength="6" name="name_member" placeholder="Enter name" />
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" required class="form-control" name="email_member" placeholder="Enter email" />
        </div>
        <div class="form-group">
            <label for="pwd">Mật khẩu:</label>
            <input type="password" required class="form-control" minlength="6" maxlength="50" name="password_member" placeholder="Enter password" />
        </div>
        <div class="submit">
            <button type="submit" class="send btn btn-primary" name="register">Đăng Ký</button>
            <a href="./" class="link btn btn-info">Trở Về</a>
        </div>
    </form>
</div>
<?php require_once './inc/footer.php'; ?>