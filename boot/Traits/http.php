<?php

namespace Boot\Traits;

use Boot\Log\Logger;
use GuzzleHttp\Client;
use Throwable;

trait http {
    /**
     * https://docs.guzzlephp.org/en/stable/quickstart.html
     * @var array
     */
    private static array $defaults = [
        'timeout' => 5.0,
    ];

    /**
     * Makes requests by using Guzzle library
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @param array $clientSettings
     * @return array
     */
    protected static function request(string $method, string $url, array $parameters, array $clientSettings = []): array
    {
        try {
            $client = new Client(array_merge(self::$defaults, $clientSettings));

            $response = $client->request($method, $url, $parameters)->getBody()->getContents();

            return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
        }

        return [];
    }

    /**
     * @param array $parameters
     * @param bool $isPost
     * @return bool|string|null
     * @see sendRequest
     */
    public static function sendTelegramRequest(array $parameters, bool $isPost = true) {
        $url = "https://api.telegram.org/bot" . $parameters['token'] . "/" . $parameters['method'];
        return self::sendRequest($parameters, $url, $isPost);
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
    public static function sendRequest(array $parameters, string $url, bool $isPost) {
        $ch = curl_init();

        if($isPost) {
            self::setCurlOptionsForPostRequest($ch, $url, $parameters);
        } else {
            self::setCurlOptionsForGetRequest($ch, $url, $parameters);
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

    /**
     * @see sendRequest
     * @param $curl
     * A CurlHandle object
     *
     * @param $url
     * API url
     *
     * @param $parameters
     * Post body parameters array
     *
     * @return bool
     */
    private static function setCurlOptionsForPostRequest($curl, $url, $parameters) {
        return curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($parameters),
        ]);
    }

    /**
     * @see sendRequest
     * @param $curl
     * A CurlHandle object
     *
     * @param $url
     * API url
     *
     * @param $parameters
     * Post body parameters array
     *
     * @return bool
     */
    private static function setCurlOptionsForGetRequest($curl, $url, $parameters) {
        return curl_setopt_array($curl, [
            CURLOPT_URL => $url . "?" . http_build_query($parameters),
            CURLOPT_POST => false,
        ]);
    }
}