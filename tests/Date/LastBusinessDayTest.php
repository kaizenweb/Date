<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;

class LastBusinessDayTest extends BaseTest {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, ?string $timezone):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date = $date->applyTimeSettings($this->_getSettings(['last_business_day' => true], $timezone));

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {
    return [
      'early'                    => ['2018-05-08 06:48:00', '2018-05-31 06:48:00', null],
      'early timezone'           => ['2018-05-08 06:48:00', '2018-05-31 06:48:00', 'America/New_York'],
      'last day week'            => ['2018-05-31 06:48:00', '2018-05-31 06:48:00', null],
      'saturday'                 => ['2018-06-30 06:48:00', '2018-06-29 06:48:00', null],
      'sunday'                   => ['2018-09-30 06:48:00', '2018-09-28 06:48:00', null],

      'first tuesday early timezone'  => ['2018-05-01 05:48:00', '2018-05-01 05:48:00', 'America/New_York'],
      'first monday early timezone'  => ['2018-10-01 05:48:00', '2018-09-29 05:48:00', 'America/New_York'],
      'saturday early timezone'  => ['2018-06-05 05:48:00', '2018-06-30 05:48:00', 'America/New_York'],
      'saturday late timezone'   => ['2018-06-30 23:48:00', '2018-07-30 23:48:00', 'Europe/Sofia'],
    ];
  }
}