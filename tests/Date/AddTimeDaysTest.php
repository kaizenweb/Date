<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class AddTimeDaysTest extends TestCase {

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
      'day'          => ['2018-05-08 06:48:00', '2018-05-14 06:48:00', ['day' => 6]],
      'day many'     => ['2018-05-08 06:48:00', '2018-07-04 06:48:00', ['day' => 57]],
      'day a lot'    => ['2018-05-08 06:48:00', '2020-04-30 06:48:00', ['day' => 723]],
      'day midnight' => ['2018-05-08 23:59:59', '2018-05-09 23:59:59', ['day' => 1]],
    ];
  }
}