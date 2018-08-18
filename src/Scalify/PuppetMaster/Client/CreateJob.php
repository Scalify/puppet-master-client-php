<?php

namespace Scalify\PuppetMaster\Client;

use JsonSerializable;

class CreateJob implements JsonSerializable
{
    /** @var string */
    protected $code;

    /** @var array */
    protected $modules;

    /** @var array */
    protected $vars;

    /**
     * CreateJob constructor.
     *
     * @param string $code
     * @param array  $vars
     * @param array  $modules
     */
    public function __construct(string $code, array $vars = [], array $modules = [])
    {
        $this->setCode($code);
        $this->setVars($vars);
        $this->setModules($modules);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasModule(string $key): bool
    {
        return array_key_exists($key, $this->modules);
    }

    /**
     * @param array $modules
     */
    public function setModules(array $modules)
    {
        $this->modules = [];
        foreach ($modules as $key => $value) {
            $this->addModule($key, $value);
        }
    }

    /**
     * Adds a module to the modules object.
     *
     * @param string $key
     * @param string $value
     */
    public function addModule(string $key, string $value)
    {
        $this->modules[$key] = $value;
    }

    /**
     * Remove a module from the current modules object.
     *
     * @param string $key
     */
    public function removeModule(string $key)
    {
        unset($this->modules[$key]);
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasVar(string $key): bool
    {
        return array_key_exists($key, $this->vars);
    }

    /**
     * @param array $vars
     */
    public function setVars(array $vars)
    {
        $this->vars = [];
        foreach ($vars as $key => $value) {
            $this->addVar($key, $value);
        }
    }

    /**
     * Adds a variable to the vars object.
     *
     * @param string $key
     * @param string $value
     */
    public function addVar(string $key, string $value)
    {
        $this->vars[$key] = $value;
    }

    /**
     * Remove a variable from the current vars object.
     *
     * @param string $key
     */
    public function removeVar(string $key)
    {
        unset($this->vars[$key]);
    }

    /**
     * Returns an associative array representation of the createJob, ready for transport or serialization.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            "code" => $this->code,
            "modules" => $this->modules,
            "vars" => $this->vars,
        ];

        foreach (['modules', 'vars'] as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }

            if (empty($data[$key])) {
                $data[$key] = new \stdClass();
            }
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

}
