<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class AccountTimezoneTest extends TestCase {

  public function testAddTimezone() {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');
    $date->addAccountTimezone('Europe/Sofia', 'NO');

    $this->assertEquals('2018-05-08 07:48:00', $date->getInFormat('datetime'));
  }

  public function testRemoveTimezone() {
    $date = new Date('2018-05-08 06:48:00', 'Europe/Oslo');
    $date->addAccountTimezone('America/New_York', 'US');
    $date = $date->removeAccountTimezone();

    $this->assertEquals('2018-05-08 06:48:00', $date->getInFormat('datetime'));
  }
}