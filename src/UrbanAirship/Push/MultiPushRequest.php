<?php
/*
Copyright 2013 Urban Airship and Contributors
*/

namespace UrbanAirship\Push;

use UrbanAirship\UALog;

class MultiPushRequest
{
    const PUSH_URL = "/api/push/";
    private $airship;

    private $pushRequest_list;

    function __construct($airship)
    {
        $this->airship = $airship;
        $this->pushRequest_list = [];
    }

    function addPushRequest(PushRequest $pushRequest)
    {
        $this->pushRequest_list[] = $pushRequest;
    }

    function getPayLoad()
    {
        return array_map(function($v) {
            return $v->getPayLoad();
        });
    }

    function send()
    {
        $uri = $this->airship->buildUrl(self::PUSH_URL);
        $logger = UALog::getLogger();

        $response = $this->airship->request("POST",
            json_encode($this->getPayload()), $uri, "application/vnd.urbanairship+json", 3);

        $payload = json_decode($response->raw_body, true);
        $logger->info("Push sent successfully.", array("push_ids" => $payload['push_ids']));
        return new PushResponse($response);
    }

}
