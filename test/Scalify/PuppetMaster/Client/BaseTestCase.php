<?php

namespace Test\Scalify\PuppetMaster\Client;

use DateTime;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function getJSONContent(string $file)
    {
        return json_decode(file_get_contents(sprintf("%s/test-data/%s.json", getcwd(), $file)), true);
    }

    protected function assertDateEquals(string $date1, string $date2, string $message)
    {
        if (!empty($date1)) {
            $date1 = DateTime::createFromFormat(DateTime::RFC3339, $date1)->format(DateTime::RFC3339);
        }

        if (!empty($date2)) {
            $date2 = DateTime::createFromFormat(DateTime::RFC3339, $date2)->format(DateTime::RFC3339);
        }

        $this->assertEquals($date1, $date2, $message);
    }
}
