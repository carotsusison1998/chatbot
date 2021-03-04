<?php
    session_start();
	function connect(){
		$connect = new PDO("mysql:host=localhost; dbname=db_chatbot", "root", "");
		return $connect;
	}
    function logout(){
		$conn = connect();
		$status = "Logout";
		$id = $_POST['id'];
        $query = "
            UPDATE tbl_members 
            SET login_status_member = $status 
            WHERE id_member = $id
		";
		echo "string";
		$statement = $conn->prepare($query);
		$statement->execute();
		echo "string2";
		
    }
    logout();
?>