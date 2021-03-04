<?php

class member{
    private $id_member;
	private $name_member;
	private $email_member;
	private $password_member;
	private $profile_member;
	private $status_member;
	private $verification_code_member;
	private $login_status_member;
	private $created_on_member;
	protected $connect;

    public function __construct()
	{
		require_once('./db/database.php');
		$database_object = new database;
		$this->connect = $database_object->connect();
	}
    public function setMember($name_member, $email_member, $password_member){
		if($this->is_valid_email_member($email_member)){
			return true;
		}else{
			$this->name_member = $name_member;
			$this->email_member = $email_member;
			$this->password_member = md5($password_member);
			$this->verification_code_member = md5(uniqid());
			$this->profile_member = "not found";
			$this->status_member = "Disabled";
			$this->login_status_member = "Logout";
			$this->created_on_member = date("d-m-Y");
		}
    }
    public function getMember(){
        $result_member = [
            "name_member" => $this->name_member,
            "email_member" => $this->email_member,
            "password_member" => $this->password_member,
            "verification_code_member" => $this->verification_code_member,
            "profile_member" => "not found",
            "status_member" => "Disabled",
            "login_status_member" => "Logout",
            "created_on_member" => date("d-m-Y"),
        ];
        return $result_member;
    }

    public function save_member(){
        $query = "
		INSERT INTO tbl_members (name_member, email_member, password_member, verification_code_member, profile_member, status_member, login_status_member, created_on_member) 
		VALUES (:name_member, :email_member, :password_member, :verification_code_member, :profile_member, :status_member, :login_status_member, :created_on_member)
		";
        $statement = $this->connect->prepare($query);
        $statement->bindParam(':name_member', $this->name_member);
        $statement->bindParam(':email_member', $this->email_member);
        $statement->bindParam(':password_member', $this->password_member);
        $statement->bindParam(':verification_code_member', $this->verification_code_member);
        $statement->bindParam(':profile_member', $this->profile_member);
        $statement->bindParam(':status_member', $this->status_member);
        $statement->bindParam(':login_status_member', $this->login_status_member);
        $statement->bindParam(':created_on_member', $this->created_on_member);
        // echo $this->verification_code_member;
        if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
    }
    public function is_valid_code_member($user_verification_code){
        $query = "
		SELECT * FROM tbl_members 
		WHERE verification_code_member = :verification_code_member
		";
		$statement = $this->connect->prepare($query);
		$statement->bindParam(':verification_code_member', $user_verification_code);
		$statement->execute();
		if($statement->rowCount() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
    }
    public function enable_user_account($verification_code_member, $status_member)
	{
		$query = "
			UPDATE tbl_members 
			SET status_member = :status_member 
			WHERE verification_code_member = :verification_code_member
		";

		$statement = $this->connect->prepare($query);
		$statement->bindParam(':status_member', $status_member);
		$statement->bindParam(':verification_code_member', $verification_code_member);
		if($statement->execute())
		{
			$stmt = $this->connect->prepare('SELECT * FROM tbl_members WHERE verification_code_member = :verification_code_member LIMIT 1');
			$stmt->bindParam(':verification_code_member', $verification_code_member);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$resultSet = $stmt->fetchAll();
			return $resultSet;
		}
		else
		{
			return false;
		}
	}
	public function is_valid_email_member($email){
		$query = "
			SELECT * FROM tbl_members 
			WHERE email_member = :email_member
		";
		$statement = $this->connect->prepare($query);
		$statement->bindParam(':email_member', $email);
		$statement->execute();
		if($statement->rowCount() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function login_member($email_member, $password_member){
		$this->email_member = $email_member;
		$this->password_member = md5($password_member);
		$query = "
			SELECT * FROM tbl_members 
			WHERE email_member = :email_member
			AND password_member = :password_member
		";
		$statement = $this->connect->prepare($query);
		$statement->bindParam(':email_member', $this->email_member);
		$statement->bindParam(':password_member', $this->password_member);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$statement->execute();
		if($statement->rowCount() > 0)
		{
			$check_member = $statement->fetchAll();
			$status_member = "Login";
			$query = "
				UPDATE tbl_members 
				SET login_status_member = :login_status_member 
				WHERE id_member = :id_member
			";
			$stmp = $this->connect->prepare($query);
			$stmp->bindParam(':login_status_member', $status_member);
			$stmp->bindParam(':id_member', $check_member[0]['id_member']);
			$stmp->execute();
			return $check_member;
		}
		else
		{
			return false;
		}
	}
	public function getListMember(){
        $query = "
            SELECT * 
            FROM tbl_members
		";
        $statement = $this->connect->prepare($query);
        if($statement->execute())
		{
			return $statement->fetchAll();
		}
		else
		{
			return false;
		}
    }
	function logoutMember($id){
		$status_member = "Logout";
        $query = "
			UPDATE tbl_members 
			SET login_status_member = :login_status_member 
			WHERE id_member = :id_member
		";

		$statement = $this->connect->prepare($query);
		$statement->bindParam(':login_status_member', $status_member);
		$statement->bindParam(':id_member', $id);
		if($statement->execute())
		{

			return true;
		}
		else
		{
			return false;
		}
    }
}

?>