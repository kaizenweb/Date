<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class AddTimeSecondsTest extends TestCase {

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
      'sec'           => ['2018-05-08 06:48:00', '2018-05-08 06:48:03', ['sec' => 3]],
      'sec some'      => ['2018-05-08 06:48:00', '2018-05-08 06:49:29', ['sec' => 89]],
      'sec midnight'  => ['2018-05-08 23:58:00', '2018-05-09 00:04:29', ['sec' => 389]],
      'sec month'     => ['2018-05-31 23:58:00', '2018-06-01 00:05:57', ['sec' => 477]],
      'sec year'      => ['2018-12-31 23:58:00', '2019-01-01 02:02:12', ['sec' => 7452]],
    ];
  }
}