<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;

class PeriodicIntervalTest extends BaseTest {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, array $settings, ?string $timezone):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date = $date->applyTimeSettings($this->_getSettings(['periodic_interval' => $settings], $timezone));

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {

    return [
      'hour'                         => ['2018-05-08 06:48:00', '2018-05-08 07:00:00', ['every' => 'hour',], null],
      'hour midnight'                => ['2018-05-08 00:34:00', '2018-05-08 01:00:00', ['every' => 'hour',], null],
      'hour before midnight'         => ['2018-05-08 23:34:00', '2018-05-09 00:00:00', ['every' => 'hour',], null],
      'hour earlier timezone'        => ['2018-05-08 04:00:00', '2018-05-08 05:00:00', ['every' => 'hour',], 'America/New_York'],
      'hour later timezone'          => ['2018-05-08 18:59:59', '2018-05-08 19:00:00', ['every' => 'hour',], 'Pacific/Auckland'],

      'before hour'                  => ['2018-05-08 06:48:00', '2018-05-08 07:30:00', ['before' => ['min' => 30], 'every' => 'hour',], null],
      'before hour midnight'         => ['2018-05-08 00:14:00', '2018-05-08 00:30:00', ['before' => ['min' => 30], 'every' => 'hour',], null],
      'before hour before midnight'  => ['2018-05-08 23:34:00', '2018-05-09 00:30:00', ['before' => ['min' => 30], 'every' => 'hour',], null],
      'before hour earlier timezone' => ['2018-05-08 04:00:00', '2018-05-08 04:30:00', ['before' => ['min' => 30], 'every' => 'hour',], 'America/New_York'],
      'before hour later timezone'   => ['2018-05-08 18:59:59', '2018-05-08 19:30:00', ['before' => ['min' => 30], 'every' => 'hour',], 'Pacific/Auckland'],

      'day'                          => ['2018-05-08 06:48:00', '2018-05-09 06:48:00', ['every' => 'day',], null],
      'day midnight'                 => ['2018-05-08 00:34:00', '2018-05-09 00:34:00', ['every' => 'day',], null],
      'day before midnight'          => ['2018-05-08 23:34:00', '2018-05-09 23:34:00', ['every' => 'day',], null],
      'day before month'             => ['2018-05-31 23:34:00', '2018-06-01 23:34:00', ['every' => 'day',], null],
      'day first month'              => ['2018-05-01 00:34:00', '2018-05-02 00:34:00', ['every' => 'day',], 'America/New_York'],
      'day earlier timezone'         => ['2018-05-08 04:00:00', '2018-05-09 04:00:00', ['every' => 'day',], 'America/New_York'],
      'day later timezone'           => ['2018-05-08 18:59:59', '2018-05-09 18:59:59', ['every' => 'day',], 'Pacific/Auckland'],

      'before day'                   => ['2018-05-08 06:48:00', '2018-05-08 15:00:00', ['before' => ['hour' => 15], 'every' => 'day',], null],
      'before day midnight'          => ['2018-05-08 06:48:00', '2018-05-09 02:00:00', ['before' => ['hour' => 2], 'every' => 'day',], null],
      'before day before midnight'   => ['2018-05-08 23:34:00', '2018-05-09 20:45:00', ['before' => ['hour' => 20, 'min' => 45], 'every' => 'day',], null],
      'before day before month'      => ['2018-05-31 23:34:00', '2018-06-01 12:00:00', ['before' => ['hour' => 12, 'min' => 0], 'every' => 'day',], null],
      'before day first month'       => ['2018-05-01 00:34:00', '2018-05-01 11:00:00', ['before' => ['hour' => 5], 'every' => 'day',], 'America/New_York'],
      'before day earlier timezone'  => ['2018-05-08 02:45:00', '2018-05-08 09:59:00', ['before' => ['hour' => 3, 'min' => 59], 'every' => 'day',], 'America/New_York'],
      'before day later timezone'    => ['2018-05-08 18:59:59', '2018-05-09 00:00:00', ['before' => ['hour' => 10], 'every' => 'day',], 'Pacific/Auckland'],

      'week'                         => ['2018-05-08 06:48:00', '2018-05-15 06:48:00', ['every' => 'week',], null],
      'week before month'            => ['2018-05-31 23:34:00', '2018-06-07 23:34:00', ['every' => 'week',], null],
      'week 28 feb'                  => ['2018-02-28 23:34:00', '2018-03-07 23:34:00', ['every' => 'week',], null],
      'week 29 feb'                  => ['2016-02-29 23:34:00', '2016-03-07 23:34:00', ['every' => 'week',], null],
      'week first month'             => ['2018-05-01 00:34:00', '2018-05-08 00:34:00', ['every' => 'week',], 'America/New_York'],
      'week earlier timezone'        => ['2018-05-08 04:00:00', '2018-05-15 04:00:00', ['every' => 'week',], 'America/New_York'],
      'week later timezone'          => ['2018-05-08 18:59:59', '2018-05-15 18:59:59', ['every' => 'week',], 'Pacific/Auckland'],

      'month'                        => ['2018-05-08 06:48:00', '2018-06-08 06:48:00', ['every' => 'month',], null],
      'month before month 30'        => ['2018-05-31 23:34:00', '2018-06-30 23:34:00', ['every' => 'month',], null],
      'month before month 28'        => ['2018-01-31 23:34:00', '2018-02-28 23:34:00', ['every' => 'month',], null],
      'month before month 29'        => ['2016-01-31 23:34:00', '2016-02-29 23:34:00', ['every' => 'month',], null],
      'month first month'            => ['2018-05-01 00:34:00', '2018-06-01 00:34:00', ['every' => 'month',], 'America/New_York'],
      'month earlier timezone'       => ['2018-05-08 04:00:00', '2018-06-08 04:00:00', ['every' => 'month',], 'America/New_York'],
      'month later timezone'         => ['2018-05-08 18:59:59', '2018-06-08 18:59:59', ['every' => 'month',], 'Pacific/Auckland'],
      'month later timezone after'   => ['2018-05-31 18:59:59', '2018-06-30 18:59:59', ['every' => 'month',], 'Pacific/Auckland'],

      'year'                         => ['2018-05-08 06:48:00', '2019-05-08 06:48:00', ['every' => 'year',], null],
      'year before month 29'         => ['2016-02-29 23:34:00', '2017-02-28 23:34:00', ['every' => 'year',], null],
      'year first month'             => ['2018-05-01 00:34:00', '2019-05-01 00:34:00', ['every' => 'year',], 'America/New_York'],
      'year earlier timezone'        => ['2018-05-08 04:00:00', '2019-05-08 04:00:00', ['every' => 'year',], 'America/New_York'],
      'year later timezone'          => ['2018-05-08 18:59:59', '2019-05-08 18:59:59', ['every' => 'year',], 'Pacific/Auckland'],
      'year later timezone after'    => ['2018-05-31 18:59:59', '2019-05-31 18:59:59', ['every' => 'year',], 'Pacific/Auckland'],
    ];
  }
}