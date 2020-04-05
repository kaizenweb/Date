<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;

class CutoffHourTest extends BaseTest {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(Date $date, string $expected, array $settings, ?string $timezone):void {
    $date = $date->applyTimeSettings($this->_getSettings(['cutoff_hour' => $settings], $timezone));

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {
    $date = new Date('2012-05-16 10:28:00', 'Europe/Oslo');

    return [
      'no add'                         => [$date, '2012-05-16 10:28:00', ['hour' => 15, 'day' => 3], null],
      'no add only hour'               => [$date, '2012-05-16 10:28:00', ['hour' => 20], null],
      'no add different day'           => [$date, '2012-05-16 10:28:00', ['hour' => 14, 'day' => 4], null],

      'add'                            => [$date, '2012-05-17 10:28:00', ['hour' => 10, 'day' => 3], null],
      'add earlier timezone'           => [$date, '2012-05-17 10:28:00', ['hour' => 1, 'day' => 3], 'America/New_York'],
      'add later timezone'             => [$date, '2012-05-17 10:28:00', ['hour' => 11, 'day' => 3], 'Europe/Sofia'],
      'add only hour'                  => [$date, '2012-05-17 10:28:00', ['hour' => 10], null],
      'add only hour earlier timezone' => [$date, '2012-05-17 10:28:00', ['hour' => 2], 'America/New_York'],
      'add only hour later timezone'   => [$date, '2012-05-17 10:28:00', ['hour' => 11], 'Europe/Sofia'],
    ];
  }
}