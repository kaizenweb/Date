<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;

class ExtendedHoldingTest extends BaseTest {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, array $settings, ?string $timezone):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date = $date->applyTimeSettings($this->_getSettings(['extended_holding' => $settings], $timezone));

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {

    return [
      'monday'     => ['2018-05-07 06:48:00', '2018-05-10 06:48:00', ['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,], null],
      'tuesday'    => ['2018-05-08 06:48:00', '2018-05-10 06:48:00', ['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,], null],
      'wednesday'  => ['2018-05-09 06:48:00', '2018-05-10 06:48:00', ['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,], null],
      'thursday'   => ['2018-05-10 06:48:00', '2018-05-10 06:48:00', ['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,], null],
      'friday'     => ['2018-05-11 06:48:00', '2018-05-17 06:48:00', ['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,], null],
      'saturday'   => ['2018-05-12 06:48:00', '2018-05-17 06:48:00', ['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,], null],
      'sunday'     => ['2018-05-13 06:48:00', '2018-05-17 06:48:00', ['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,], null],

      'monday earlier timezone' => ['2018-05-07 04:48:00', '2018-05-11 04:48:00', ['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,], 'America/New_York'],
      'thursday later timezone' => ['2018-05-10 18:48:00', '2018-05-16 18:48:00', ['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,], 'Pacific/Auckland'],
    ];
  }
}