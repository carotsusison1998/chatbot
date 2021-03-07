<?php
class group_chat{
    private $id_member_create;
    private $name_group;
    private $rule_group;

    private $id_group;
    private $id_member;
    private $message;
    protected $connect;

    public function __construct()
	{
		require_once('./db/database.php');
		$database_object = new database;
		$this->connect = $database_object->connect();
	}
    public function setGroupChat($id_member_create, $name_group, $rule_group){
        $this->id_member_create = $id_member_create;
        $this->name_group = $name_group;
        $this->rule_group = $rule_group;
    }
    public function getChatOneOne(){
        $result = array(
            "id_member_create" => $this->id_member_create,
            "name_group" => $this->name_group,
            "rule_group" => $this->rule_group,
        );
        return $result;
    }
    public function saveGroupChat(){
        $query = "
            INSERT INTO tbl_groups(id_member_create, name_group, rule_group)
            VALUES(:id_member_create, :name_group, :rule_group)
        ";
        $statement = $this->connect->prepare($query);
        $statement->bindParam(':id_member_create', $this->id_member_create);
        $statement->bindParam(':name_group', $this->name_group);
        $statement->bindParam(':rule_group', $this->rule_group);
        if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
    }
    public function getGroupChatById($id){
        $query = "
            SELECT DISTINCT id_member, id_group, name_group, rule_group
            FROM tbl_chat_group, tbl_groups
            WHERE id_member = $id
            AND tbl_chat_group.id_group = tbl_groups.id
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
    public function checkIssetInGroup($id_group, $id_member){
        $query = "
            SELECT * 
            FROM tbl_chat_group 
            WHERE id_group = $id_group
            AND id_member = $id_member
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
    public function getMessageInGroup($id_group){
        $query = "
            SELECT * 
            FROM tbl_chat_group 
            WHERE id_group = $id_group
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
    public function setMessageInGroup($id_group, $id_member, $message){
        $this->id_group = $id_group;
        $this->id_member = $id_member;
        $this->message = $message;
    }
    public function getMessageInGroupToClass(){
        return array(
            "id_group" => $this->id_group,
            "id_member" => $this->id_member,
            "message" => $this->message,
        );
    }
    public function saveMessageInGroup(){
        $query = "
            INSERT INTO tbl_chat_group(id_group, id_member, message)
            VALUES(:id_group, :id_member, :message)
        ";
        $statement = $this->connect->prepare($query);
        $statement->bindParam(':id_group', $this->id_group);
        $statement->bindParam(':id_member', $this->id_member);
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
    public function getMemberInGroup($id_group){
        $query = "
            SELECT DISTINCT id_member
            FROM tbl_chat_group 
            WHERE id_group = $id_group
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