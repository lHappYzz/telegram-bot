<?php

namespace Boot\Traits;

trait Http {
    /**
     * @param array $parameters
     * The parameters array should contain bot token, method and other optional fields
     *
     * @param bool $isPost
     * Should http should use post method otherwise get method will be used
     *
     * @return string|bool|null
     */
    public function sendRequest($parameters, $isPost = true) {

        $url = "https://api.telegram.org/bot" . $parameters['token'] . "/" . $parameters['method'];

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