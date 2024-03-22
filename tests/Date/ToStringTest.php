<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class ToStringTest extends TestCase {

  public function testToString():void {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');
    $date->addAccountTimezone('America/New_York', 'US');

    $this->assertEquals('Tue, May 8th, 2018', sprintf((string) $date));
  }
}