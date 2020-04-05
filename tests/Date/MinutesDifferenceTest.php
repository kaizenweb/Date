<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class MinutesDifferenceTest extends TestCase {

  public function testDifference():void {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');
    $diff = new Date('2018-05-08 09:00:23', 'Europe/Oslo');

    $this->assertEquals(132, $date->minutesDifference($diff));
  }

  public function testDifferenceNegative():void {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');
    $diff = new Date('2018-05-08 09:00:23', 'Europe/Oslo');

    $this->assertEquals(132, $diff->minutesDifference($date));
  }

  public function testDifferenceWithTimezones():void {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');
    $diff = new Date('2018-05-09 09:00:23', 'Pacific/Auckland');

    $this->assertEquals(972, $date->minutesDifference($diff));
  }
}