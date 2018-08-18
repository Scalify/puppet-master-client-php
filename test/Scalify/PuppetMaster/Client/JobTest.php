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
        $this->assertEquals($this->job->getUuid(), $this->response["uuid"], "uuid is not equal");
        $this->assertEquals($this->job->getStatus(), $this->response["status"], "status is not equal");
        $this->assertEquals($this->job->getCode(), $this->response["code"], "code is not equal");
        $this->assertEquals($this->job->getVars(), $this->response["vars"], "vars is not equal");
        $this->assertEquals($this->job->getModules(), $this->response["modules"], "modules is not equal");
        $this->assertEquals($this->job->getError(), $this->response["error"], "error is not equal");
        $this->assertEquals($this->job->getLogs(), $this->response["logs"], "logs is not equal");
        $this->assertEquals($this->job->getResults(), $this->response["results"], "results is not equal");
        $this->assertEquals($this->job->getDuration(), $this->response["duration"], "duration is not equal");
        $this->assertDateEquals($this->job->getCreatedAt()->format(DateTime::RFC3339), $this->response["created_at"], "created_at is not equal");
        $this->assertDateEquals($this->job->getStartedAt()->format(DateTime::RFC3339), $this->response["started_at"], "started_at is not equal");
        $this->assertDateEquals($this->job->getFinishedAt()->format(DateTime::RFC3339), $this->response["finished_at"], "finished_at is not equal");
    }

    public function testToArray()
    {
        $data = $this->job->toArray();

        $this->assertEquals($data["uuid"], $this->response["uuid"], "uuid is not equal");
        $this->assertEquals($data["status"], $this->response["status"], "status is not equal");
        $this->assertEquals($data["code"], $this->response["code"], "code is not equal");
        $this->assertEquals($data["vars"], $this->response["vars"], "vars is not equal");
        $this->assertEquals($data["modules"], $this->response["modules"], "modules is not equal");
        $this->assertEquals($data["error"], $this->response["error"], "error is not equal");
        $this->assertEquals($data["logs"], $this->response["logs"], "logs is not equal");
        $this->assertEquals($data["results"], $this->response["results"], "results is not equal");
        $this->assertEquals($data["duration"], $this->response["duration"], "duration is not equal");
        $this->assertDateEquals($data["created_at"], $this->response["created_at"], "created_at is not equal");
        $this->assertDateEquals($data["started_at"], $this->response["started_at"], "started_at is not equal");
        $this->assertDateEquals($data["finished_at"], $this->response["finished_at"], "finished_at is not equal");
    }
}
