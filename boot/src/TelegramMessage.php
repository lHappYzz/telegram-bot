<?php


namespace Boot\Src;


class TelegramMessage {
    private $events = [
        [
            'type' => 'message',
            'description' => 'message was sent'
        ],
        [
            'type' => 'edited_message',
            'description' => 'message was edited'
        ]
    ];
    private $event = [];

    private $messageID;
    private $from;
    private $chat;

    private $date;
    private $text;

    public function __construct($update) {
        $this->messageID = $update['message']['message_id'];
        $this->setEvent($update);

        $this->from = $update[$this->event['type']]['from'];
        $this->chat = $update[$this->event['type']]['chat'];

        $this->date = $update[$this->event['type']]['date'];
        $this->text = $update[$this->event['type']]['text'];
    }
    private function setEvent($update) {
        foreach ($this->events as $event) {
            if (array_key_exists($event['type'], $update)) {
                $this->event = $event;
            }
        }
    }
    public function getEventDescription() {
        return $this->event['description'];
    }

    public function getChat() {
        return $this->chat;
    }

    public function getMessageID() {
        return $this->messageID;
    }

    public function getMessageText() {
        return $this->text;
    }

    public function getMessageDate($format = 'Y-m-d H:i:s') {
        return date($format, $this->date);
    }

}