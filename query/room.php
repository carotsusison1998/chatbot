<?php

class room{
	private $id_member;
	private $message;
	private $created_on;
	protected $connect;

    public function __construct()
	{
		require_once('./db/database.php');
		$database_object = new database;
		$this->connect = $database_object->connect();
	}
    public function setRoom($id_member, $message){
        $this->id_member = $id_member;
        $this->message = $message;
        $this->created_on = date("d-m-Y");
    }
    public function getRoom(){
        $result_room = [
            "id_member" => $this->id_member,
            "message" => $this->message,
            "created_on" => $this->created_on
        ];
        return $result_room;
    }
    public function save_room(){
        $query = "
		INSERT INTO tbl_room (id_member, message, created_on) 
		VALUES (:id_member, :message, :created_on)
		";
        $statement = $this->connect->prepare($query);
        $statement->bindParam(':id_member', $this->id_member);
        $statement->bindParam(':message', $this->message);
        $statement->bindParam(':created_on', $this->created_on);
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