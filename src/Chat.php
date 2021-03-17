<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require_once dirname(__DIR__) . '/query/room.php';
require_once dirname(__DIR__) . '/query/chat_one_one.php';
require_once dirname(__DIR__) . '/query/group_chat.php';

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connections' . "\n"
            , $from->resourceId, $msg, $numRecv);

        $data = json_decode($msg, true);
        if($data['action'] == "chat-member"){
            $chat_one = new \chat_one_one;
            $chat_one->setChatOneOne($data['id'], $data['id_recieve'], $data['msg']);
            $chat_one->saveChatOneOne();
            // print_r($result);
            foreach ($this->clients as $client) {
                if($from === $client)
                {
                    $data['from'] = 'Me';
                }
                else
                {
                    $data['from'] = "you";
                }
                $client->send(json_encode($data));
            }
        }else if($data['action'] == "chat-room"){
            $chat_room = new \room;
            $chat_room->setRoom($data['id'], $data['msg']);
            $chat_room->saveRoom();
            foreach ($this->clients as $client) {
                if($from === $client)
                {
                    $data['from'] = 'Me';
                }
                else
                {
                    $data['from'] = "you";
                }

                $client->send(json_encode($data));
            }
        }else if($data['action'] == "chat-group"){
            $group_chat = new \group_chat;
            $group_chat->setMessageInGroup($data['id_group'], $data['id'], $data['msg']);
            $group_chat->saveMessageInGroup();
            foreach ($this->clients as $client) {
                if($from === $client)
                {
                    $data['from'] = 'Me';
                }
                else
                {
                    $data['from'] = "you";
                }

                $client->send(json_encode($data));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

?>