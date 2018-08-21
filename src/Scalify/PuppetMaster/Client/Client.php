<?php

namespace Scalify\PuppetMaster\Client;

use GuzzleHttp\ClientInterface as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    /** @var string */
    private $endpoint;

    /** @var string */
    private $apiToken;

    /** @var GuzzleClient */
    private $client;

    /**
     * The amount of time to wait between fetching an updated job from the puppet-master api.
     *
     * @var int
     */
    private $syncSleepMs = 500;

    /**
     * Client constructor.
     *
     * @param GuzzleClient $client
     * @param string       $endpoint
     * @param string       $apiToken
     */
    public function __construct(GuzzleClient $client, string $endpoint, string $apiToken)
    {
        $this->endpoint = trim($endpoint, "/");
        $this->apiToken = trim($apiToken);
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function setSyncSleepMs(int $syncSleepMs)
    {
        $this->syncSleepMs = $syncSleepMs;
    }

    /**
     * @inheritDoc
     */
    public function getJobs(int $page, int $perPage): array
    {
        return $this->getJobsByStatus("", $page, $perPage);
    }

    /**
     * @inheritDoc
     */
    public function getJobsByStatus(string $status, int $page, int $perPage): array
    {
        $request = new Request("GET", $this->buildUrl(sprintf("/jobs?status=%s&page=%d&per_page=%d", $status, $page, $perPage)));
        $response = $this->send($request);

        if ($response->getStatusCode() !== 200) {
            throw new ClientException(sprintf("Unexpected response: %d %s", $response->getStatusCode(), $response->getReasonPhrase()), $request, $response);
        }

        $data = $this->getJsonBody($request, $response);

        $jobs = [];
        foreach ($data as $values) {
            $jobs[] = new Job($values);
        }

        return $jobs;
    }

    /**
     * Builds the request URL out of the baseUrl and given path.
     *
     * @param string $path
     *
     * @return string
     */
    private function buildUrl(string $path)
    {
        return sprintf("%s/%s", $this->endpoint, trim($path, "/"));
    }

    /**
     * Execute a request, adding required JSON headers and authorization.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    protected function send(RequestInterface $request): ResponseInterface
    {
        $request = $request->withHeader("Authorization", sprintf("Bearer %s", $this->apiToken));
        $request = $request->withHeader("Content-Type", "application/json");
        $request = $request->withHeader("Accept", "application/json");

        try {
            $response = $this->client->send($request);
        } catch (GuzzleException $e) {
            if ($e->getCode() === 401) {
                throw new ClientException("Authorization failed. Did you specify the right api token?", $request, null, $e);
            }

            throw new ClientException(sprintf("Failed to execute request (code %d): %s", $e->getCode(), $e->getMessage()), $request, null, $e);
        }

        return $response;
    }

    /**
     * Returns the JSON data field from given response.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return array
     */
    private function getJsonBody(RequestInterface $request, ResponseInterface $response): array
    {
        $data = json_decode($response->getBody(), true);
        if (!$data || !is_array($data) || !array_key_exists("data", $data)) {
            throw new ClientException("Response body does not contain a valid JSON object.", $request, $response);
        }

        if (!is_array($data) || !is_array($data["data"])) {
            throw new ClientException("Not sure what happened. The list jobs endpoint didn't return a list. :worried:", $request, $response);
        }

        return $data["data"];
    }

    /**
     * @inheritDoc
     */
    public function createJob(CreateJob $createJob): Job
    {
        $request = new Request("POST", $this->buildUrl("/jobs"), [], json_encode($createJob->toArray()));
        $response = $this->send($request);

        if ($response->getStatusCode() !== 200) {
            throw new ClientException(sprintf("Unexpected response: %d %s", $response->getStatusCode(), $response->getReasonPhrase()), $request, $response);
        }

        $data = $this->getJsonBody($request, $response);

        return new Job($data);
    }

    /**
     * @inheritdoc
     */
    public function executeSynchronously(CreateJob $createJob): JOb
    {
        $job = $this->createJob($createJob);

        do {
            $job = $this->getJob($job->getUUID());
            if ($job->getStatus() !== Job::STATUS_DONE) {
                usleep($this->syncSleepMs * 1000);
            }
        } while ($job->getStatus() !== Job::STATUS_DONE);

        return $job;
    }

    /**
     * @inheritDoc
     */
    public function getJob(string $uuid): Job
    {
        $request = new Request("GET", $this->buildUrl(sprintf("/jobs/%s", $uuid)));
        $response = $this->send($request);

        if ($response->getStatusCode() !== 200) {
            throw new ClientException(sprintf("Unexpected response: %d %s", $response->getStatusCode(), $response->getReasonPhrase()), $request, $response);
        }

        $data = $this->getJsonBody($request, $response);

        return new Job($data);
    }

    /**
     * @inheritDoc
     */
    public function deleteJob(string $uuid)
    {
        $request = new Request("DELETE", $this->buildUrl(sprintf("/jobs/%s", $uuid)));
        $response = $this->send($request);

        if ($response->getStatusCode() !== 204) {
            throw new ClientException(sprintf("Unexpected response: %d %s", $response->getStatusCode(), $response->getReasonPhrase()), $request, $response);
        }
    }

}
