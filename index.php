<?php
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $curl = curl_init();
    $headers = array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded",
    );
    curl_setopt_array($curl, array(
        CURLOPT_URL             => "https://webapi.teamviewer.com/api/v1/oauth2/token",
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_ENCODING        => "",
        CURLOPT_MAXREDIRS       => 10,
        CURLOPT_SSL_VERIFYPEER  => FALSE,
        CURLOPT_TIMEOUT         => 30,
        CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST   => "POST",
        CURLOPT_POSTFIELDS      => "grant_type=authorization_code&code=" . $code . "&redirect_uri=http://teamapi.local&client_id=144486-dvi1zlY2jaAcgr77Ymoc&client_secret=f7X6tnIcWH4CD7nRuE5w",
        CURLOPT_HTTPHEADER      => $headers
    ));
    curl_setopt($curl, CURLOPT_VERBOSE, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo $err;
    } else {
        /**
         * Success received token bearer.  Creating Instant Meeting
         */
        $resdata = json_decode($response);
        if (!empty($resdata->access_token)) {
            $token = $resdata->access_token;
            $curlMeeting = curl_init();
            curl_setopt_array($curlMeeting, array(
                CURLOPT_URL             => "https://webapi.teamviewer.com/api/v1/meetings",
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_ENCODING        => "",
                CURLOPT_MAXREDIRS       => 10,
                CURLOPT_TIMEOUT         => 30,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST   => "POST",
                CURLOPT_POSTFIELDS      => "instant=true",
                CURLOPT_HTTPHEADER      => array(
                    "authorization: Bearer " . $token,
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));
            $responseMeeting = curl_exec($curlMeeting);
            $errMeeting = curl_error($curlMeeting);
            curl_close($curlMeeting);
            if ($errMeeting) {
                echo "cURL Error #:" . $errMeeting;
            } else {
                echo $responseMeeting;
            }
        }
    }
} else {

    /**
     * Creating login request for get code from team viwer
     */
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL             => "http://login.teamviewer.com/oauth2/authorize?response_type=code&client_id=144486-dvi1zlY2jaAcgr77Ymoc&redirect_uri=http://teamapi.local/",
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_ENCODING        => "",
        CURLOPT_MAXREDIRS       => 10,
        CURLOPT_TIMEOUT         => 30,
        CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST   => "GET",
        CURLOPT_HTTPHEADER      => array(
            "cache-control: no-cache",
            "postman-token: 3957368a-f97e-c6eb-bf6a-5a32553a7e99"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}
?>