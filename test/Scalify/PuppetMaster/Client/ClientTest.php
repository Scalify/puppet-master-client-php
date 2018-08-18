<?php

namespace Test\Scalify\PuppetMaster\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Scalify\PuppetMaster\Client\Client;
use Scalify\PuppetMaster\Client\CreateJob;
use Scalify\PuppetMaster\Client\Job;

class ClientTest extends BaseTestCase
{
    /** @var array[] */
    private $requests = [];

    /** @var Client */
    private $client;

    protected function getSentRequest(): RequestInterface
    {
        return $this->requests[0]["request"];
    }

    /**
     * @inheritDoc
     */
    protected function createMockedClient(array $responses)
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::history($this->requests));
        $client = new GuzzleClient(['handler' => $handler]);

        $this->client = new Client($client, "http://localhost", "test");
    }

    public function testAuthorizationHeader()
    {
        $this->createMockedClient([
            new Response(200, [], $this->getFileContent("get-job-response")),
        ]);

        $this->client->getJob("a47e71b6-1bb4-44e0-a808-e340e7d441e9");

        $this->assertCount(1, $this->requests);
        $req = $this->getSentRequest();
        $this->assertTrue($req->hasHeader("Authorization"));
        $this->assertEquals("Bearer test", $req->getHeaderLine("Authorization"));
    }

    public function testContentTypeHeader()
    {
        $this->createMockedClient([
            new Response(200, [], $this->getFileContent("get-job-response")),
        ]);

        $this->client->getJob("a47e71b6-1bb4-44e0-a808-e340e7d441e9");

        $this->assertCount(1, $this->requests);
        $req = $this->getSentRequest();

        $this->assertTrue($req->hasHeader("Content-Type"));
        $this->assertEquals("application/json", $req->getHeaderLine("Content-Type"));

        $this->assertTrue($req->hasHeader("Accept"));
        $this->assertEquals("application/json", $req->getHeaderLine("Accept"));
    }

    /**
     * @expectedException \Scalify\PuppetMaster\Client\ClientException
     * @expectedExceptionMessage Authorization failed. Did you specify the right api token?
     */
    public function testGetJobsUnauthorized()
    {
        $this->createMockedClient([
            new Response(401, [], "Unauthorized"),
        ]);

        $this->client->getJobs(1, 100);
    }

    public function testGetJobs()
    {
        $this->createMockedClient([
            new Response(200, [], $this->getFileContent("get-jobs-response")),
        ]);

        $jobs = $this->client->getJobs(1, 100);
        $this->assertCount(2, $jobs);

        $this->assertCount(1, $this->requests);
        $req = $this->getSentRequest();
        $this->assertEquals("GET", $req->getMethod());
        $this->assertEquals("/jobs", $req->getUri()->getPath());
        $this->assertEquals("status=&page=1&per_page=100", $req->getUri()->getQuery());

        /** @var Job $job */
        foreach ($jobs as $job) {
            $this->assertInstanceOf(Job::class, $job);
            $this->assertNotEmpty($job->getUUID());
        }
    }

    public function testGetJobsByStatus()
    {
        $this->createMockedClient([
            new Response(200, [], $this->getFileContent("get-jobs-response")),
        ]);

        $jobs = $this->client->getJobsByStatus("done", 1, 100);
        $this->assertCount(2, $jobs);

        $this->assertCount(1, $this->requests);
        $req = $this->getSentRequest();
        $this->assertEquals("GET", $req->getMethod());
        $this->assertEquals("/jobs", $req->getUri()->getPath());
        $this->assertEquals("status=done&page=1&per_page=100", $req->getUri()->getQuery());

        /** @var Job $job */
        foreach ($jobs as $job) {
            $this->assertInstanceOf(Job::class, $job);
            $this->assertNotEmpty($job->getUUID());
        }
    }

    public function testGetJob()
    {
        $this->createMockedClient([
            new Response(200, [], $this->getFileContent("get-job-response")),
        ]);

        $uuid = "a47e71b6-1bb4-44e0-a808-e340e7d441e9";
        $job = $this->client->getJob($uuid);

        $this->assertInstanceOf(Job::class, $job);
        $this->assertEquals($job->getUUID(), $uuid);

        $this->assertCount(1, $this->requests);
        $req = $this->getSentRequest();
        $this->assertEquals("GET", $req->getMethod());
        $this->assertEquals("/jobs/a47e71b6-1bb4-44e0-a808-e340e7d441e9", $req->getUri()->getPath());
    }

    public function testCreateJob()
    {
        $this->createMockedClient([
            new Response(200, [], $this->getFileContent("create-response")),
        ]);

        $request = $this->getFileContent("create-request");
        $requestData = $this->getJSONContent("create-request");
        $createJob = new CreateJob($requestData["code"], $requestData["vars"], $requestData["modules"]);
        $job = $this->client->createJob($createJob);

        $this->assertCount(1, $this->requests);
        $req = $this->getSentRequest();
        $this->assertEquals("POST", $req->getMethod());
        $this->assertEquals("/jobs", $req->getUri()->getPath());

        $this->assertInstanceOf(Job::class, $job);
        $this->assertNotEmpty($job->getUUID());
    }

    public function testDeleteJob()
    {
        $this->createMockedClient([
            new Response(204, [], null),
        ]);

        $uuid = "a47e71b6-1bb4-44e0-a808-e340e7d441e9";
        $this->client->deleteJob($uuid);

        $this->assertCount(1, $this->requests);
        $req = $this->getSentRequest();
        $this->assertEquals("DELETE", $req->getMethod());
        $this->assertEquals("/jobs/a47e71b6-1bb4-44e0-a808-e340e7d441e9", $req->getUri()->getPath());
    }
}
