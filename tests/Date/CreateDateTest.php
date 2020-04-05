<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class CreateDateTest extends TestCase {

  public function testWithDate():void {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');

    $this->assertEquals('2018-05-08 06:48:00', $date->getInFormat('datetime'));
  }

  public function testWithoutDate():void {
    $date = new Date(null, 'Europe/Oslo');

    $object = new \DateTime('now', new \DateTimeZone('Europe/Oslo'));

    $this->assertEquals($object->format('Y-m-d H:i'), $date->getFormat('Y-m-d H:i'));
  }

  public function testWithUnixTimestamp():void {
    $date = new Date('@1337156880', 'Europe/Oslo');

    $this->assertEquals('2012-05-16 10:28:00', $date->getInFormat('datetime'));
  }

  public function testWithTimezoneInDate():void {
    $date = new Date('2012-05-16T08:28:00+00:00', 'Europe/Oslo');

    $this->assertEquals('2012-05-16 10:28:00', $date->getInFormat('datetime'));

    //Without T in string, and both in same timezone
    $date = new Date('2012-05-16 10:28:00+02:00', 'Europe/Sofia');

    $this->assertEquals('2012-05-16 11:28:00', $date->getInFormat('datetime'));
  }
}