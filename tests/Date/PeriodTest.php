<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;

class PeriodTest extends BaseTest {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, array $settings, ?string $timezone):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date = $date->applyTimeSettings($this->_getSettings(['period' => $settings], $timezone));

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {
    //Base check only, as it utilize the same system as the "AddTime[system]" tests.
    return [
      'multiple'        => ['2018-05-08 06:48:00', '2018-05-08 08:50:12', ['sec' => 12, 'min' => 2, 'hour' => 2], null],
      'multiple day'    => ['2018-05-08 06:48:00', '2019-05-12 08:50:12', ['sec' => 12, 'min' => 2, 'hour' => 2, 'day' => 4, 'year' => 1], null],
      'multiple v1'     => ['2018-05-08 06:48:00', '2018-05-11 08:50:12', ['sec' => 12, 'min' => 2, 'hour' => 2, 'day' => 3], 'Europe/Sofia'],
      'multiple v2'     => ['2018-05-08 06:48:00', '2020-05-11 08:50:12', ['sec' => 12, 'min' => 2, 'hour' => 2, 'day' => 3, 'year' => 2], 'Pacific/Auckland'],
    ];
  }
}