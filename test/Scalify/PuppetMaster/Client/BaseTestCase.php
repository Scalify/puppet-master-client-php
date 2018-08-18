<?php

namespace Test\Scalify\PuppetMaster\Client;

use PHPUnit\Framework\TestCase;
use Scalify\PuppetMaster\Client\CreateJob;

class BaseTestCase extends TestCase
{
    /**
     * @return CreateJob
     */
    protected function newTestCreateJob(): CreateJob
    {
        $data = $this->getCreateJobRequestContent();
        $job = new CreateJob($data["code"], $data["vars"], $data["modules"]);

        return $job;
    }

    protected function getCreateJobRequestContent()
    {
        return json_decode(file_get_contents(getcwd() . "/test-data/create-request.json"), true);
    }
}
