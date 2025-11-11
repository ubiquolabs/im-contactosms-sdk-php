<?php

function notEmptyValue($value){
    return $value;
}


class ApiWrapper {

    var $apiKey;
    var $apiSecret;
    var $apiUrl;
    var $assoc;

    function __construct($apiKey, $apiSecret, $apiUrl, $assoc){
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        if ($apiUrl[strlen($apiUrl)-1] !== '/') {
            $apiUrl .= '/' ;
        }
        $this->apiUrl = $apiUrl;
        $this->assoc = $assoc;
    }

    public function checkContactStatus($status){
        if ($status){
            if (!in_array($status,array(
                "SUSCRIBED","CONFIRMED","CANCELLED","INVITED",
            ))) throw new Exception("Status is not a valid status");
        }
    }

    public function checkInteger($value, $boolean=false){
        if (!$value) return;
        if (!is_numeric($value)) throw new Exception("Value $value is not numeric.");
        if ($boolean && $value!='1' && $value!='0') throw new Exception("Value $value is not 0 or 1.");
    }

    public function checkDate(&$value, $required=false){
        if (!$value && !$required) return;
        if (!is_numeric($value)) $value = strtotime($value);
        if (!$value) throw new Exception("Value $value is not a date.");
        $value = date("Y-m-d H:i:s",$value);
    }

    public function checkArray($value, $required=false){
        if (!is_array($value)) throw new Exception("$value is not an array");
    }


    public function getParamsString($params){
        if ($params) {
            if (!is_array($params) && !is_object($params)) throw new Exception('expected array or object in $params');
            ksort($params);
            $params = http_build_query(array_filter($params, "notEmptyValue"));
        }
        return $params;
    }

    public function getBodyString($body){
        if ($body) {
            if (!is_array($body) && !is_object($body)) throw new Exception('expected array or object in $body');
            // Use JSON_UNESCAPED_UNICODE to preserve UTF-8 characters (like Python's ensure_ascii=False)
            $body = json_encode(array_filter($body,"notEmptyValue"), JSON_UNESCAPED_UNICODE);
        }
        return $body;
    }

    public function get($endpoint, $params=null){
        $url = $this->apiUrl.$endpoint;
        $paramsString = $this->getParamsString($params);
        return $this->send($url,$paramsString, 'GET', null);
    }

    public function post($endpoint, $params=null, $body=null){
        $url = $this->apiUrl.$endpoint;
        $paramsString = $this->getParamsString($params);
        $bodyString = $this->getBodyString($body);
        return $this->send($url,$paramsString, 'POST', $bodyString);
    }

    public function put($endpoint, $params=null, $body=null){
        $url = $this->apiUrl.$endpoint;
        $paramsString = $this->getParamsString($params);
        $bodyString = $this->getBodyString($body);
        return $this->send($url,$paramsString, 'PUT', $bodyString);
    }

    public function delete($endpoint, $params=null){
        $url = $this->apiUrl.$endpoint;
        $paramsString = $this->getParamsString($params);
        return $this->send($url,$paramsString, 'DELETE', null);
    }

    public function send($url, $params, $method, $body){
        if ($params) $url = $url."?".$params;
        $datetime = gmdate("D, d M Y H:i:s T");
        $paramsStr = $params ? $params : '';
        $bodyStr = $body ? $body : '';
        $authentication = $this->apiKey.$datetime.$paramsStr.$bodyStr;
        $hash = hash_hmac("sha1",$authentication, $this->apiSecret,true);
        $hash = base64_encode($hash);
        $headers = array(
            "Content-type: application/json; charset=utf-8",
            "Date: $datetime",
            "Authorization: IM $this->apiKey:$hash",
            "X-IM-ORIGIN: IM_SDK_PHP",
            "Accept-Encoding: gzip, deflate",
        );
        
        $options = array(
            'http' => array(
                'header' => $headers,
                'method' => $method,
                'content' => $body,
                'ignore_errors' => true,
            ),
        );
        $context = stream_context_create($options);
        $data = file_get_contents($url,false, $context);
        $rawBody = $data;

        $contentEncoding = null;
        foreach ($http_response_header as $headerLine) {
            if (stripos($headerLine, 'Content-Encoding:') === 0) {
                $contentEncoding = trim(substr($headerLine, strlen('Content-Encoding:')));
                break;
            }
        }

        if ($data !== false && $contentEncoding && stripos($contentEncoding, 'gzip') !== false) {
            $decodedData = @gzdecode($data);
            if ($decodedData !== false) {
                $data = $decodedData;
            }
        }

        $decoded = null;
        $utf8Body = $data;
        if ($data !== false) {
            $decoded = json_decode($data,$this->assoc);
            if (json_last_error() === JSON_ERROR_UTF8) {
                if (function_exists('mb_convert_encoding')) {
                    $utf8Body = mb_convert_encoding($data, 'UTF-8', 'UTF-8, ISO-8859-1, ISO-8859-15');
                } elseif (function_exists('iconv')) {
                    $utf8Body = iconv('ISO-8859-1', 'UTF-8', $data);
                } else {
                    $utf8Body = $data;
                }
                $decoded = json_decode($utf8Body, $this->assoc);
            }
        }
        $has_code = preg_match('/\ (\d+)\ /', $http_response_header[0], $response_code);
        if ($has_code) $response_code = $response_code[1];
        else $response_code = null;
        $has_status = preg_match('/\ ([^\ ]+)$/', $http_response_header[0], $status);
        if ($has_status) $status = $status[1];
        $result = array(
            'code' => $response_code+0,
            'status' => $status,
            'ok' => $status=="OK",
            'response_headers' => $http_response_header,
            'raw_body' => $rawBody,
            'body_utf8' => $utf8Body,
            'data' => $decoded,
        );
        return $this->assoc?$result:(object)$result;
    }
}