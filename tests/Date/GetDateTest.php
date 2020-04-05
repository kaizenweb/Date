<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class GetDateTest extends TestCase {

  public function testWithDate():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    $object = $date->getDate('2018-05-08 06:48:00');

    $this->assertEquals('2018-05-08 06:48:00', $object->getInFormat('datetime'));
  }

  public function testWithoutDate():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    $object = $date->getDate(null);

    $this->assertNotEquals($date->getInFormat('datetime'), $object->getInFormat('datetime'));
  }

  public function testKeepSystemTimezone():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    $object = $date->getDate(null);

    $this->assertEquals($date->getSystemTimezoneName(), $object->getSystemTimezoneName());
  }

  public function testKeepAccountTimezone():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');
    $date->addAccountTimezone('America/New_York', 'US');

    $object = $date->getDate('2018-05-08 06:48:00');

    $this->assertEquals('2018-05-08 00:48:00', $object->getInFormat('datetime'));
  }
}