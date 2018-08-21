<?php

namespace Test\Scalify\PuppetMaster\Client;

use DateTime;
use Scalify\PuppetMaster\Client\Job;

class JobTest extends BaseTestCase
{
    /** @var array */
    private $response;

    /** @var Job */
    private $job;

    public function setUp()
    {
        $this->response = $this->getJSONContent("get-job-response")["data"];
        $this->job = new Job($this->response);
    }

    public function testConstructAndFillSetsFields()
    {
        $this->assertEquals($this->response["uuid"], $this->job->getUuid(), "uuid is not equal");
        $this->assertEquals($this->response["status"], $this->job->getStatus(), "status is not equal");
        $this->assertEquals($this->response["code"], $this->job->getCode(), "code is not equal");
        $this->assertEquals($this->response["vars"], $this->job->getVars(), "vars is not equal");
        $this->assertEquals($this->response["modules"], $this->job->getModules(), "modules is not equal");
        $this->assertEquals($this->response["error"], $this->job->getError(), "error is not equal");
        $this->assertEquals($this->response["logs"], $this->job->getLogs(), "logs is not equal");
        $this->assertEquals($this->response["results"], $this->job->getResults(), "results is not equal");
        $this->assertEquals($this->response["duration"], $this->job->getDuration(), "duration is not equal");
        $this->assertDateEquals($this->response["created_at"], $this->job->getCreatedAt()->format(DateTime::RFC3339), "created_at is not equal");
        $this->assertDateEquals($this->response["started_at"], $this->job->getStartedAt()->format(DateTime::RFC3339), "started_at is not equal");
        $this->assertDateEquals($this->response["finished_at"], $this->job->getFinishedAt()->format(DateTime::RFC3339), "finished_at is not equal");
    }

    public function testToArray()
    {
        $data = $this->job->toArray();

        $this->assertEquals($this->response["uuid"], $data["uuid"], "uuid is not equal");
        $this->assertEquals($this->response["status"], $data["status"], "status is not equal");
        $this->assertEquals($this->response["code"], $data["code"], "code is not equal");
        $this->assertEquals($this->response["vars"], $data["vars"], "vars is not equal");
        $this->assertEquals($this->response["modules"], $data["modules"], "modules is not equal");
        $this->assertEquals($this->response["error"], $data["error"], "error is not equal");
        $this->assertEquals($this->response["logs"], $data["logs"], "logs is not equal");
        $this->assertEquals($this->response["results"], $data["results"], "results is not equal");
        $this->assertEquals($this->response["duration"], $data["duration"], "duration is not equal");
        $this->assertDateEquals($this->response["created_at"], $data["created_at"], "created_at is not equal");
        $this->assertDateEquals($this->response["started_at"], $data["started_at"], "started_at is not equal");
        $this->assertDateEquals($this->response["finished_at"], $data["finished_at"], "finished_at is not equal");
    }
}
