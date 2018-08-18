<?php

namespace Test\Scalify\PuppetMaster\Client;

use Scalify\PuppetMaster\Client\CreateJob;

class CreateJobTest extends BaseTestCase
{
    /** @var CreateJob */
    private $job;

    /** @var array */
    private $request;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->request = $this->getJSONContent("create-request");
        $this->job = new CreateJob($this->request["code"], $this->request["vars"], $this->request["modules"]);
    }

    public function testConstructSetsFields()
    {
        $this->assertNotEmpty($this->job->getCode());
        $this->assertArrayHasKey("shared", $this->job->getModules());
        $this->assertArrayHasKey("page", $this->job->getVars());
    }

    public function testAddRemoveModule()
    {
        $this->assertCount(1, $this->job->getModules());
        $this->assertArrayNotHasKey("test", $this->job->getModules());
        $this->assertFalse($this->job->hasModule("test"));

        $this->job->addModule("test", "export const content = \"teschd\"");

        $this->assertCount(2, $this->job->getModules());
        $this->assertArrayHasKey("test", $this->job->getModules());
        $this->assertTrue($this->job->hasModule("test"));

        $this->job->removeModule("test");

        $this->assertCount(1, $this->job->getModules());
        $this->assertArrayNotHasKey("test", $this->job->getModules());
    }

    public function testAddRemoveVar()
    {
        $this->assertCount(1, $this->job->getVars());
        $this->assertArrayNotHasKey("test", $this->job->getVars());
        $this->assertFalse($this->job->hasVar("test"));

        $this->job->addVar("test", "export const content = \"teschd\"");

        $this->assertCount(2, $this->job->getVars());
        $this->assertArrayHasKey("test", $this->job->getVars());
        $this->assertTrue($this->job->hasVar("test"));

        $this->job->removeVar("test");

        $this->assertCount(1, $this->job->getVars());
        $this->assertArrayNotHasKey("test", $this->job->getVars());
    }

    public function testToArray()
    {
        $data = $this->job->toArray();

        $this->assertEquals($data["code"], $this->request["code"]);
        $this->assertEquals($data["vars"], $this->request["vars"]);
        $this->assertEquals($data["modules"], $this->request["modules"]);
    }
}
