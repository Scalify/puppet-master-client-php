# puppet-master-client-php

PHP client for the [puppet-master.io](https://puppet-master.io) public API. Puppet-master makes the execution of website interactions
super simple by abstracting the code execution behind a HTTP API, scheduling the job for you in a scalable
manner. For more information please head over to the [puppet-master docs](https://docs.puppet-master.io).


## installation

```bash
composer require scalify/puppet-master-client:~1.0
```

## example usage

```php
<?php

use Scalify\PuppetMaster\Client\Client;
use Scalify\PuppetMaster\Client\ClientException;
use Scalify\PuppetMaster\Client\ClientInterface;
use Scalify\PuppetMaster\Client\CreateJob;
use Scalify\PuppetMaster\Client\Job;

require __DIR__ . '/vendor/autoload.php';

/**
 * @param $client
 */
function printCurrentJobs(ClientInterface $client)
{
    foreach (["created", "queued", "done"] as $status) {
        $jobs = $client->getJobsByStatus($status, 1, 1000);
        echo(sprintf("API currently has %s jobs at status %s", count($jobs), $status) . PHP_EOL);
    }

    $jobs = $client->getJobs(1, 1000);
    echo(sprintf("API currently has %s jobs at all", count($jobs)) . PHP_EOL);

    /** @var Job $job */
    foreach ($jobs as $job) {
        echo(sprintf("API has job %s at status %s", $job->getUUID(), $job->getStatus()) . PHP_EOL);
    }
}

function newTestCreateJob(): CreateJob
{
    $data = json_decode(file_get_contents("test-data/create-request.json"), true);

    return new CreateJob($data["code"], $data["vars"], []);
}

try {
    $client = new Client(new \GuzzleHttp\Client(), "http://localhost", "puppet");

    printCurrentJobs($client);

    $createdJob = $client->createJob(newTestCreateJob());
    echo(sprintf("Created Job %s at status %s", $createdJob->getUUID(), $createdJob->getStatus()) . PHP_EOL);

    do {
        $gotJob = $client->getJob($createdJob->getUUID());
        echo(sprintf("Got Job %s at status %s", $gotJob->getUUID(), $gotJob->getStatus()) . PHP_EOL);

        if ($gotJob->getStatus() !== Job::STATUS_DONE) {
            echo("Sleeping for 1 second ..." . PHP_EOL);
            sleep(1);
        }
    } while ($gotJob->getStatus() !== Job::STATUS_DONE);

    $client->deleteJob($createdJob->getUUID());
    echo(sprintf("Delete job %s", $createdJob->getUUID()) . PHP_EOL);
} catch (ClientException $e) {
    echo($e->getMessage() . PHP_EOL);
    exit(1);
}
```

## License

Copyright 2018 Scalify GmbH

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

		http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
