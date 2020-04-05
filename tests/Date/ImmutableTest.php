<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class ImmutableTest extends BaseTest {

  public function testVerifyThatItIsImmutable():void {
    $date = new Date(null, 'Europe/Oslo');

    $this->expectException(\BadMethodCallException::class);

    $date->__construct(null, 'Europe/Oslo');
  }

  public function testGetDateTimeObjectImmutability():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    $object = $date->getDateTime();

    $object->modify('+1 day');

    $this->assertEquals('2012-05-16 10:28:00', $date->getInFormat('datetime'));
  }

  public function testGetDateObjectImmutability():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    $object = $date->getDate('2018-05-08 06:48:00');

    $this->assertEquals('2012-05-16 10:28:00', $date->getInFormat('datetime'));
  }

  public function testRemoveAccountTimezoneImmutability():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    $object = $date->removeAccountTimezone();

    $object->addTime(['day' => 1]);

    $this->assertEquals('2012-05-16 10:28:00', $date->getInFormat('datetime'));
  }

  public function testAddTimeImmutability():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    $object = $date->addTime(['day' => 1]);

    $this->assertEquals('2012-05-16 10:28:00', $date->getInFormat('datetime'));
  }

  public function testRemoveTimeImmutability():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    $object = $date->removeTime(['day' => 1]);

    $this->assertEquals('2012-05-16 10:28:00', $date->getInFormat('datetime'));
  }

  public function testApplyTimeSettingsImmutability():void {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    $object = $date->applyTimeSettings($this->_getSettings(['time_of_day' => ['hour' => 12, 'min' => 15]], null));

    $this->assertEquals('2012-05-16 10:28:00', $date->getInFormat('datetime'));
  }
}