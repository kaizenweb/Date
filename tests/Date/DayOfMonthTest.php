<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;

class DayOfMonthTest extends BaseTest {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, int $day, ?string $timezone):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date = $date->applyTimeSettings($this->_getSettings(['day_of_month' => $day], $timezone));

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {
    return [
      'normal'                          => ['2018-05-08 06:48:00', '2018-05-15 06:48:00', 15, null],
      'last day'                        => ['2018-06-28 06:48:00', '2018-06-30 06:48:00', 31, null],
      'last day february'               => ['2018-02-06 23:48:00', '2018-02-28 23:48:00', 29, null],
      'same day'                        => ['2018-05-08 06:48:00', '2018-05-08 06:48:00', 8, null],
      'same day earlier timezone'       => ['2018-05-09 04:48:00', '2018-05-09 04:48:00', 8, 'America/New_York'],
      'same day later timezone'         => ['2018-05-07 20:48:00', '2018-05-07 20:48:00', 8, 'Pacific/Auckland'],

      'start of month earlier timezone' => ['2018-05-01 04:48:00', '2018-04-16 04:48:00', 15, 'America/New_York'],
      'end of month later timezone'     => ['2018-05-31 20:48:00', '2018-06-14 20:48:00', 15, 'Pacific/Auckland'],
    ];
  }
}