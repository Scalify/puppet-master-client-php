<?php

namespace Test\Scalify\PuppetMaster\Client;

use DateTime;
use Scalify\PuppetMaster\Client\Job;

class JobTest extends BaseTestCase
{
    private function getJobResponseContent()
    {
        return $this->getJSONContent("get-job-response")["data"];
    }

    public function testConstructAndFillSetsFields()
    {
        $response = $this->getJobResponseContent();
        $job = new Job($response);

        $this->assertEquals($job->getUuid(), $response["uuid"], "uuid is not equal");
        $this->assertEquals($job->getStatus(), $response["status"], "status is not equal");
        $this->assertEquals($job->getCode(), $response["code"], "code is not equal");
        $this->assertEquals($job->getVars(), $response["vars"], "vars is not equal");
        $this->assertEquals($job->getModules(), $response["modules"], "modules is not equal");
        $this->assertEquals($job->getError(), $response["error"], "error is not equal");
        $this->assertEquals($job->getLogs(), $response["logs"], "logs is not equal");
        $this->assertEquals($job->getResults(), $response["results"], "results is not equal");
        $this->assertEquals($job->getDuration(), $response["duration"], "duration is not equal");
        $this->assertDateEquals($job->getCreatedAt()->format(DateTime::RFC3339), $response["created_at"], "created_at is not equal");
        $this->assertDateEquals($job->getStartedAt()->format(DateTime::RFC3339), $response["started_at"], "started_at is not equal");
        $this->assertDateEquals($job->getFinishedAt()->format(DateTime::RFC3339), $response["finished_at"], "finished_at is not equal");
    }

    public function testToArray()
    {
        $response = $this->getJobResponseContent();
        $job = new Job($response);
        $data = $job->toArray();

        $this->assertEquals($data["uuid"], $response["uuid"], "uuid is not equal");
        $this->assertEquals($data["status"], $response["status"], "status is not equal");
        $this->assertEquals($data["code"], $response["code"], "code is not equal");
        $this->assertEquals($data["vars"], $response["vars"], "vars is not equal");
        $this->assertEquals($data["modules"], $response["modules"], "modules is not equal");
        $this->assertEquals($data["error"], $response["error"], "error is not equal");
        $this->assertEquals($data["logs"], $response["logs"], "logs is not equal");
        $this->assertEquals($data["results"], $response["results"], "results is not equal");
        $this->assertEquals($data["duration"], $response["duration"], "duration is not equal");
        $this->assertDateEquals($data["created_at"], $response["created_at"], "created_at is not equal");
        $this->assertDateEquals($data["started_at"], $response["started_at"], "started_at is not equal");
        $this->assertDateEquals($data["finished_at"], $response["finished_at"], "finished_at is not equal");
    }
}
