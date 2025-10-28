<?php
require_once("ApiWrapper.php");

class ShortlinksResource extends ApiWrapper {

    function __construct($apiKey, $apiSecret, $apiUrl, $assoc){
        parent::__construct($apiKey, $apiSecret, $apiUrl, $assoc);
    }

    public function listShortlinks($startDate = null, $endDate = null, $limit = null, $offset = null, $id = null){
        $this->checkDate($startDate);
        $this->checkDate($endDate);
        $this->checkInteger($limit);
        $this->checkInteger($offset);

        $params = array();
        
        if ($id) {
            $params['id'] = $id;
        } else {
            if ($startDate) $params['start_date'] = $startDate;
            if ($endDate) $params['end_date'] = $endDate;
            if ($limit) $params['limit'] = $limit;
            if ($offset !== null) $params['offset'] = $offset;
        }

        return $this->get("short_link/", $params);
    }

    public function getShortlinkById($id){
        if (!$id) {
            throw new Exception("Shortlink ID is required");
        }
        return $this->listShortlinks(null, null, null, null, $id);
    }

    public function createShortlink($longUrl, $name = null, $status = "ACTIVE"){
        if (!$longUrl) {
            throw new Exception("long_url is required");
        }

        if ($status && !in_array($status, array("ACTIVE", "INACTIVE"))) {
            throw new Exception("Status must be ACTIVE or INACTIVE");
        }

        $body = array(
            'long_url' => $longUrl,
            'name' => $name,
            'status' => $status,
        );

        return $this->post("short_link", null, $body);
    }

    public function updateShortlinkStatus($id, $newStatus){
        if (!$id) {
            throw new Exception("Shortlink ID is required");
        }

        if (!$newStatus || !in_array($newStatus, array("ACTIVE", "INACTIVE"))) {
            throw new Exception("Status is required and must be ACTIVE or INACTIVE");
        }

        $params = array('id' => $id);
        $body = array('status' => $newStatus);

        return $this->put("short_link/$id/status", $params, $body);
    }
}

