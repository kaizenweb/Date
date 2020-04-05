<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class AccountFormatTest extends TestCase {

  /**
   * @dataProvider formatProvider
   */
  public function testFormats(string $startDate, string $expected, string $accountTimezone, string $accountDateFormat, string $formatType):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date->addAccountTimezone($accountTimezone, $accountDateFormat);

    $this->assertEquals($expected, $date->getAccountFormat($formatType));
  }

  public function formatProvider():array {
    return [
      'hour'              => ['2018-05-08 06:48:00', '7:48', 'Europe/Sofia', 'NO', 'hour'],
      'hour v2'           => ['2018-05-08 06:48:00', '0:48 am', 'America/New_York', 'US', 'hour'],

      'basic date'        => ['2018-05-08 06:48:00', '8/5/2018', 'Europe/Sofia', 'NO', 'basicdate'],
      'basic date v2'     => ['2018-05-08 06:48:00', '5/8/2018', 'America/New_York', 'US', 'basicdate'],

      'date'              => ['2018-05-08 06:48:00', 'Tue, 8 May 2018', 'Europe/Sofia', 'NO', 'date'],
      'date v2'           => ['2018-05-08 06:48:00', 'Tue, May 8th, 2018', 'America/New_York', 'US', 'date'],
    ];
  }

}