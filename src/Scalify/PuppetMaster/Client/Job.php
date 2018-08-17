<?php

namespace Scalify\PuppetMaster\Client;

use DateTime;
use JsonSerializable;

class Job implements JsonSerializable
{
    const STATUS_CREATED = "created";
    const STATUS_QUEUED = "queued";
    const STATUS_DONE = "done";

    /** @var string */
    protected $uuid;

    /** @var string */
    protected $status;

    /** @var string */
    protected $code;

    /** @var array */
    protected $modules;

    /** @var array */
    protected $vars;

    /** @var string */
    protected $error;

    /** @var array */
    protected $logs;

    /** @var array */
    protected $results;

    /** @var int */
    protected $duration;

    /** @var DateTime */
    protected $createdAt;

    /** @var DateTime */
    protected $startedAt;

    /** @var DateTime */
    protected $finishedAt;

    /**
     * Job constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * Fills the given data into the respective properties.
     *
     * @param array $data
     */
    protected function fill(array $data)
    {
        $this->uuid = $data["uuid"] ?: "";
        $this->status = $data["status"] ?: "";
        $this->code = $data["code"] ?: "";
        $this->modules = $data["modules"] ?: [];
        $this->vars = $data["vars"] ?: [];
        $this->error = $data["error"] ?: "";
        $this->logs = $data["logs"] ?: [];
        $this->results = $data["results"] ?: [];
        $this->duration = $data["duration"] ?: 0;
        $this->createdAt = $data["created_at"] ?: null;
        $this->startedAt = $data["started_at"] ?: null;
        $this->finishedAt = $data["finished_at"] ?: null;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getUUID(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getStartedAt(): DateTime
    {
        return $this->startedAt;
    }

    /**
     * @return DateTime
     */
    public function getFinishedAt(): DateTime
    {
        return $this->finishedAt;
    }
}
