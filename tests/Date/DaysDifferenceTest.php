<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class DaysDifferenceTest extends TestCase {

  public function testDifference():void {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');
    $diff = new Date('2018-05-16 23:00:23', 'Europe/Oslo');

    $this->assertEquals(8, $date->daysDifference($diff));
  }

  public function testDifferenceNegative():void {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');
    $diff = new Date('2018-05-16 23:00:23', 'Europe/Oslo');

    $this->assertEquals(8, $diff->daysDifference($date));
  }

  public function testDifferenceWithTimezones():void {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');
    $diff = new Date('2018-05-17 09:00:23', 'Pacific/Auckland');

    $this->assertEquals(8, $date->daysDifference($diff));
  }
}