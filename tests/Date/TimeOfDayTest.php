<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;

class TimeOfDayTest extends BaseTest {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, array $settings, ?string $timezone):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date = $date->applyTimeSettings($this->_getSettings(['time_of_day' => $settings], $timezone));

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {
    return [
      'normal'                          => ['2018-05-08 06:48:00', '2018-05-08 02:00:00', ['hour' => 2, 'min' => 0], null],
      'last day'                        => ['2018-06-28 06:48:00', '2018-06-28 22:30:00', ['hour' => 22, 'min' => 30], null],
      'same day earlier timezone'       => ['2018-05-09 04:48:00', '2018-05-08 09:10:00', ['hour' => 3, 'min' => 10], 'America/New_York'],
      'same day later timezone'         => ['2018-05-07 18:34:00', '2018-05-07 18:45:00', ['hour' => 4, 'min' => 45], 'Pacific/Auckland'],

      'start of month earlier timezone' => ['2018-05-01 04:34:00', '2018-05-01 00:00:00', ['hour' => 18, 'min' => 0], 'America/New_York'],
      'end of month later timezone'     => ['2018-05-31 20:34:00', '2018-05-31 16:20:00', ['hour' => 2, 'min' => 20], 'Pacific/Auckland'],
    ];
  }
}