<?php


namespace Boot\Src;


class TelegramMessage {
    private $messageID;
    private $date;
    private $text;
    private $commandClassName;
    private TelegramMessage $replyToMessage;


    public function __construct($messageData) {
        $this->messageID = $messageData['message_id'];
        $this->date = $messageData['date'];
        $this->text = $messageData['text'];
        $this->setCommandClassName();

        $this->setReplyToMessage($messageData);
    }
    private function setReplyToMessage($messageData) {
        if (array_key_exists('reply_to_message', $messageData)) {
            $this->replyToMessage = new TelegramMessage($messageData['reply_to_message']);
        }
    }

    private function setCommandClassName() {
        if ($this->isCommand()) {
            $this->commandClassName = str_replace('/', '', $this->text) . 'Command';
        } else {
            $this->commandClassName = '';
        }
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

    public function getCommandClassName() {
        return $this->commandClassName;
    }

    /**
     * Get message that was replied otherwise null is returned,
     * so always check your var for not being null
     *
     * @return TelegramMessage|null
     */
    public function getRepliedMessage() {
        if (isset($this->replyToMessage)) {
            return $this->replyToMessage;
        }
        return null;
    }

    public function isCommand() {
        if ($this->text[0] == '/') {
            if (strpos($this->text, ' ') === false) {
                return true;
            }
        }
        return false;
    }


}