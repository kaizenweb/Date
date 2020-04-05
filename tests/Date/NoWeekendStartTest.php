<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;

class NoWeekendStartTest extends BaseTest {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, ?string $timezone):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date = $date->applyTimeSettings($this->_getSettings(['no_weekend_start' => true], $timezone));

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {
    return [
      'not weekend'                    => ['2018-05-08 06:48:00', '2018-05-08 06:48:00', null],
      'not weekend timezone'           => ['2018-05-08 06:48:00', '2018-05-08 06:48:00', 'America/New_York'],
      'not weekend earlier timezone'   => ['2018-05-05 05:48:00', '2018-05-05 05:48:00', 'America/New_York'],
      'not weekend later timezone'     => ['2018-05-06 23:48:00', '2018-05-06 23:48:00', 'Europe/Sofia'],

      'weekend saturday'               => ['2018-05-05 11:48:00', '2018-05-07 11:48:00', null],
      'weekend sunday'                 => ['2018-05-06 11:48:00', '2018-05-07 11:48:00', null],
      'weekend earlier timezone'       => ['2018-05-07 04:48:00', '2018-05-08 04:48:00', 'America/New_York'],
      'weekend later timezone'         => ['2018-05-04 20:48:00', '2018-05-06 20:48:00', 'Pacific/Auckland'],
    ];
  }
}