<?php
require_once("ApiWrapper.php");

class ShortlinksResource extends ApiWrapper {

    function __construct($apiKey, $apiSecret, $apiUrl, $assoc){
        parent::__construct($apiKey, $apiSecret, $apiUrl, $assoc);
    }

    public function listShortlinks($params = array()){
        if (isset($params['id'])) {
            $requestParams = array('id' => $params['id']);
            return $this->get("short_link/", $requestParams);
        }

        $requestParams = array();
        if (isset($params['start_date'])) {
            $requestParams['start_date'] = $params['start_date'];
        }
        if (isset($params['end_date'])) {
            $requestParams['end_date'] = $params['end_date'];
        }
        if (isset($params['limit'])) {
            $this->checkInteger($params['limit']);
            $requestParams['limit'] = $params['limit'];
        }
        if (isset($params['offset'])) {
            $this->checkInteger($params['offset']);
            $requestParams['offset'] = $params['offset'];
        }

        return $this->get("short_link/", $requestParams);
    }

    public function getShortlinkById($id){
        if (!$id) {
            throw new Exception("Shortlink ID is required");
        }
        return $this->get("short_link/", array('id' => $id));
    }

    public function createShortlink($longUrl, $name = null, $status = "ACTIVE", $alias = null){
        if (!$longUrl) {
            throw new Exception("long_url is required");
        }

        if ($status && !in_array($status, array("ACTIVE", "INACTIVE"))) {
            throw new Exception("Status must be ACTIVE or INACTIVE");
        }

        if ($name !== null) {
            $name = trim($name);
            if ($name === '') {
                $name = null;
            } elseif (strlen($name) > 50) {
                throw new Exception("Name must be 50 characters or fewer");
            }
        }

        if ($alias !== null) {
            $alias = trim($alias);
            if ($alias === '') {
                throw new Exception("Alias cannot be empty");
            }
            if (strlen($alias) > 30) {
                throw new Exception("Alias must be 30 characters or fewer");
            }
            if (preg_match('/\s/', $alias)) {
                throw new Exception("Alias cannot contain whitespace");
            }
        }

        $body = array(
            'long_url' => $longUrl,
            'name' => $name,
            'status' => $status,
        );

        if ($alias !== null) {
            $body['alias'] = $alias;
        }

        return $this->post("short_link", null, $body);
    }

    public function updateShortlinkStatus($id, $newStatus){
        if (!$id) {
            throw new Exception("Shortlink ID is required");
        }

        if (!$newStatus || !in_array($newStatus, array("ACTIVE", "INACTIVE"))) {
            throw new Exception("Status is required and must be ACTIVE or INACTIVE");
        }

        if ($newStatus === "ACTIVE") {
            throw new Exception("Shortlinks cannot be reactivated; the API rejects ACTIVE updates.");
        }

        $params = array('id' => $id);
        $body = array('status' => $newStatus);

        return $this->put("short_link/$id/status", $params, $body);
    }
}

