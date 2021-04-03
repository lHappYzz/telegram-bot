<?php

namespace Boot\Traits;

trait Http {

    /**
     * @param array $parameters
     * @param bool $isPost
     * @return bool|string|null
     * @see sendRequest
     */
    public function sendTelegramRequest(array $parameters, bool $isPost = true) {
        $url = "https://api.telegram.org/bot" . $parameters['token'] . "/" . $parameters['method'];
        return $this->sendRequest($parameters, $url, $isPost);
    }

    /**
     * @param array $parameters
     * The parameters array should contain bot token, method and other optional fields
     * that service API can work with
     *
     * @param $url
     * API url
     *
     * @param bool $isPost
     * If true then post http method will be used otherwise get method will be used
     *
     * @return string|bool|null
     */
    public function sendRequest(array $parameters, string $url, bool $isPost = true) {

        $ch = curl_init();

        if($isPost) {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        } else {
            curl_setopt($ch, CURLOPT_URL, $url . "?" . http_build_query($parameters));
            curl_setopt($ch, CURLOPT_POST, 0);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $serverResponse = curl_exec($ch);

        curl_close ($ch);

        if($serverResponse) {
            $serverResponse = json_decode($serverResponse, 1);
        } else {
            $serverResponse = null;
        }

        return $serverResponse;
    }
}