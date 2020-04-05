<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class GetDateTimeTest extends TestCase {

  public function testGetDateTimeObject() {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');

    $this->assertInstanceOf(\DateTime::class, $date->getDateTime());
  }
}