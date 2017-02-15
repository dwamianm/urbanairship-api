<?php
/*
Copyright 2013 Urban Airship and Contributors
*/

namespace UrbanAirship\Push;

use UrbanAirship\Airship;
use UrbanAirship\UALog;

class MultiPushRequest
{
    const PUSH_URL = "/api/push/";

    /**
     * @var Airship
     */
    private $airship;

    private $pushRequest_list;

    private static $LIMIT_PER_PUSH = 50;

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
        }, $this->pushRequest_list);
    }

    function send()
    {
        $nSent = 0;
        $payload_cutted = array_chunk($this->getPayLoad(), self::$LIMIT_PER_PUSH);

        foreach ($payload_cutted as $payload) {
            $uri = $this->airship->buildUrl(self::PUSH_URL);

            $response = $this->airship->request("POST",
                json_encode($payload), $uri, "application/vnd.urbanairship+json", 3);

            $logger = UALog::getLogger();
            $payload = json_decode($response->raw_body, true);
            $logger->info("Push sent successfully.", array("push_ids" => $payload['push_ids']));
            $nSent += count($payload['push_ids']);
        }

        return $nSent;

    }

}
