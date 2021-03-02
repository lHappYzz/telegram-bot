<?php

namespace Boot\Src;

use Boot\Traits\Http;
use Exception;


class TelegramRequest {

    use Http;

    /**
     * An array created from json object sent by telegram
     *
     * @var array
     * @see parseTelegramRequest
     */
    private $update;

    /**
     * Marker that indicates if telegram request parsed ok
     *
     * @var bool
     */
    public $isParseOk = false;

    public function __construct() {
        $this->update = $this->parseTelegramRequest();
        $this->isTelegramRequestParsedOk();
    }

    /**
     * Check if telegram request parsed ok
     *
     * @see $isParseOk
     * @throws Exception
     */
    public function isTelegramRequestParsedOk() {
        if (!$this->isParseOk) {
            throw new Exception('Telegram request error');
        }
    }

    private function parseTelegramRequest() {
        $tgData = json_decode(file_get_contents('php://input'),1);
        if (isset($tgData)) {
            $old_log = file_get_contents("log.txt");
            file_put_contents("log.txt", print_r($tgData, 1) . "\n*********************\n\n" . $old_log);
            $this->isParseOk = true;
        } else {
            return ['ok' => false];
        }
        return  $tgData;
    }

    /**
     * Returns array data created from json object sent by telegram
     *
     * @see parseTelegramRequest
     * @return array
     */
    public function getUpdate() {
        return $this->update;
    }
}