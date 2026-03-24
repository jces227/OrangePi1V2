<?php

require_once __DIR__ . "/../config_loader.php";

class OmadaAPI {

    public static function login() {

        $loginInfo = [
            "name" => OPERATOR_USERNAME,
            "password" => OPERATOR_PASSWORD
        ];

        $headers = [
            "Content-Type: application/json",
            "Accept: application/json"
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE_PATH);
        curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE_PATH);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_URL,
            "https://" . CONTROLLER_IP . ":" . CONTROLLER_PORT . "/" . CONTROLLER_ID . "/api/v2/hotspot/login"
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginInfo));

        $res = curl_exec($ch);
        curl_close($ch);

        $resObj = json_decode($res);

        if ($resObj->errorCode == 0) {
            file_put_contents(TOKEN_FILE_PATH, $resObj->result->token);
        }

        return $resObj;
    }

    public static function authorize($clientMac, $apMac, $ssidName, $radioId, $site, $time) {

        $token = trim(file_get_contents(TOKEN_FILE_PATH));

        $authInfo = [
            "clientMac" => $clientMac,
            "apMac" => $apMac,
            "ssidName" => $ssidName,
            "radioId" => $radioId,
            "site" => $site,
            "time" => $time,
            "authType" => 4
        ];

        $headers = [
            "Content-Type: application/json",
            "Accept: application/json",
            "Csrf-Token: " . $token
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE_PATH);
        curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE_PATH);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_URL,
            "https://" . CONTROLLER_IP . ":" . CONTROLLER_PORT . "/" . CONTROLLER_ID . "/api/v2/hotspot/extPortal/auth"
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($authInfo));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
}