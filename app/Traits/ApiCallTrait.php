<?php


namespace App\Traits;


trait ApiCallTrait
{


    public function curlPost($url, $data = [], $optionalHeaders = [])
    {
        $headers = [
            "Content-Type: application/json",
        ];

        if (!empty($optionalHeaders)) {
            $headers = array_merge($headers, $optionalHeaders);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }


    public function curlGet($url, array $queryData = [], $optionalHeaders = [])
    {

        if (!empty($queryData) and count($queryData) > 0) {
            $queryString = http_build_query($queryData);
            $url = $url . "?" . $queryString;
        }

        $headers = [
            "Content-Type: application/json",
        ];

        if (!empty($optionalHeaders)) {
            $headers = array_merge($headers, $optionalHeaders);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

}
