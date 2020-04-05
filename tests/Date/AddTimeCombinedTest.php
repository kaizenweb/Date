<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class AddTimeCombinedTest extends TestCase {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, array $settings):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date = $date->addTime($settings);

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {
    return [
      'v1'        => ['2018-05-08 06:48:00', '2018-07-08 07:51:00', ['min' => 3, 'hour' => 1, 'month' => 2]],
      'v2'        => ['2018-05-08 06:48:00', '2018-05-17 08:17:00', ['min' => 89, 'day' => 2, 'week' => 1]],
      'v3'        => ['2018-05-08 06:48:00', '2019-05-08 09:48:00', ['hour' => 3, 'year' => 1]],
      'v4'        => ['2018-05-08 06:48:00', '2018-11-25 23:48:00', ['hour' => 89, 'week' => 2, 'month' => 6]],
    ];
  }
}