<?php

namespace Test\Scalify\PuppetMaster\Client;

class CreateJobTest extends BaseTestCase
{
    public function testConstructSetsFields()
    {
        $job = $this->newTestCreateJob();

        $this->assertNotEmpty($job->getCode());
        $this->assertArrayHasKey("shared", $job->getModules());
        $this->assertArrayHasKey("page", $job->getVars());
    }

    public function testAddRemoveModule()
    {
        $job = $this->newTestCreateJob();

        $this->assertCount(1, $job->getModules());
        $this->assertArrayNotHasKey("test", $job->getModules());
        $this->assertFalse($job->hasModule("test"));

        $job->addModule("test", "export const content = \"teschd\"");

        $this->assertCount(2, $job->getModules());
        $this->assertArrayHasKey("test", $job->getModules());
        $this->assertTrue($job->hasModule("test"));

        $job->removeModule("test");

        $this->assertCount(1, $job->getModules());
        $this->assertArrayNotHasKey("test", $job->getModules());
    }

    public function testAddRemoveVar()
    {
        $job = $this->newTestCreateJob();

        $this->assertCount(1, $job->getVars());
        $this->assertArrayNotHasKey("test", $job->getVars());
        $this->assertFalse($job->hasVar("test"));

        $job->addVar("test", "export const content = \"teschd\"");

        $this->assertCount(2, $job->getVars());
        $this->assertArrayHasKey("test", $job->getVars());
        $this->assertTrue($job->hasVar("test"));

        $job->removeVar("test");

        $this->assertCount(1, $job->getVars());
        $this->assertArrayNotHasKey("test", $job->getVars());
    }

    public function testToArray()
    {
        $job = $this->newTestCreateJob();
        $request = $this->getCreateJobRequestContent();

        $data = $job->toArray();

        $this->assertEquals($data["code"], $request["code"]);
        $this->assertEquals($data["vars"], $request["vars"]);
        $this->assertEquals($data["modules"], $request["modules"]);
    }
}
