<?php


namespace Boot\Src;


class telegramMessage {
    private $messageID;
    private $date;
    private $text;
    private $commandClassName;
    private telegramMessage $replyToMessage;
    private array $photo = [];
    private string $caption = '';

    public function __construct($messageData) {
        $this->messageID = $messageData['message_id'];
        $this->date = $messageData['date'];
        $this->text = $messageData['text'];

        if (array_key_exists('photo', $messageData)) {
            if (array_key_exists('caption', $messageData)) {
                $this->caption = $messageData['caption'];
            }
            foreach ($messageData['photo'] as $photoData) {
                $this->photo[] = new telegramPhotoSize($photoData);
            }
        }

        $this->setCommandClassName();

        $this->setReplyToMessage($messageData);
    }
    private function setReplyToMessage($messageData) {
        if (array_key_exists('reply_to_message', $messageData)) {
            $this->replyToMessage = new telegramMessage($messageData['reply_to_message']);
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
     * Returns array of telegramPhotoSize object.
     * Read more: https://core.telegram.org/bots/api#photosize
     * @return array
     */
    public function getPhoto(): array {
        return $this->photo;
    }

    public function getCaption(): string {
        return $this->caption;
    }

    /**
     * Get message that was replied otherwise null is returned,
     * so always check your var for not being null
     *
     * @return telegramMessage|null
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