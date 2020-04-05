<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class RemoveTimeCombinedTest extends TestCase {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, array $settings):void {
    $date = new Date($startDate, 'Europe/Oslo');
    $date = $date->removeTime($settings);

    $this->assertEquals($expected, $date->getInFormat('datetime'));
  }

  public function validResultProvider():array {
    return [
      'v1'        => ['2018-05-08 06:48:00', '2018-03-08 05:45:00', ['min' => 3, 'hour' => 1, 'month' => 2]],
      'v2'        => ['2018-05-08 06:48:00', '2018-04-29 05:19:00', ['min' => 89, 'day' => 2, 'week' => 1]],
      'v3'        => ['2018-05-08 06:48:00', '2017-05-08 03:48:00', ['hour' => 3, 'year' => 1]],
      'v4'        => ['2018-05-08 06:48:00', '2017-10-20 13:48:00', ['hour' => 89, 'week' => 2, 'month' => 6]],
    ];
  }
}