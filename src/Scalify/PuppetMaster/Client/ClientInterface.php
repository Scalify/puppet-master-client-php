<?php

namespace Scalify\PuppetMaster\Client;

interface ClientInterface
{
    /**
     * Gets a paginated list of jobs.
     *
     * @param int $page
     * @param int $perPage
     *
     * @return Job[]
     * @throws ClientException
     */
    public function getJobs(int $page, int $perPage): array;

    /**
     * Gets a paginated list of jobs that match the given status.
     *
     * @param string $status
     * @param int    $page
     * @param int    $perPage
     *
     * @return Job[]
     * @throws ClientException
     */
    public function getJobsByStatus(string $status, int $page, int $perPage): array;

    /**
     * Create a job.
     *
     * @param CreateJob $createJob
     *
     * @return Job
     * @throws ClientException
     */
    public function createJob(CreateJob $createJob): Job;

    /**
     * @param string $uuid
     *
     * @return Job
     * @throws ClientException
     */
    public function getJob(string $uuid): Job;

    /**
     * Deletes a given Job.
     *
     * @param string $uuid
     *
     * @return void
     */
    public function deleteJob(string $uuid);
}
