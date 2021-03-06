<?php
class chat_one_one{
    private $id_member_one;
    private $id_member_two;
    private $message;
    protected $connect;

    public function __construct()
	{
		require_once('./db/database.php');
		$database_object = new database;
		$this->connect = $database_object->connect();
	}
    public function setChatOneOne($id_member_one, $id_member_two, $message){
        $this->id_member_one = $id_member_one;
        $this->id_member_two = $id_member_two;
        $this->message = $message;
    }
    public function getChatOneOne(){
        $result = array(
            "id_member_one" => $this->id_member_one,
            "id_member_two" => $this->id_member_two,
            "message" => $this->message,
        );
        return $result;
    }
    public function saveChatOneOne(){
        $query = "
            INSERT INTO tbl_chat_member(id_member_one, id_member_two, message)
            VALUES(:id_member_one, :id_member_two, :message)
        ";
        $statement = $this->connect->prepare($query);
        $statement->bindParam(':id_member_one', $this->id_member_one);
        $statement->bindParam(':id_member_two', $this->id_member_two);
        $statement->bindParam(':message', $this->message);
        if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
    }
    public function getListChatOneOne($id_member_one, $id_member_two){
        $query = "
            SELECT * 
            FROM tbl_chat_member
            WHERE id_member_one = $id_member_one AND id_member_two = $id_member_two
            OR id_member_two = $id_member_one AND id_member_one = $id_member_two
            ORDER BY created_on ASC
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
}
?>