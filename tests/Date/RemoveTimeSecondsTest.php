<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class RemoveTimeSecondsTest extends TestCase {

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
      'sec'           => ['2018-05-08 06:48:00', '2018-05-08 06:47:57', ['sec' => 3]],
      'sec some'      => ['2018-05-08 06:48:00', '2018-05-08 06:46:31', ['sec' => 89]],
      'sec midnight'  => ['2018-05-09 00:04:29', '2018-05-08 23:58:00', ['sec' => 389]],
      'sec month'     => ['2018-06-01 00:05:57', '2018-05-31 23:58:00', ['sec' => 477]],
      'sec year'      => ['2019-01-01 02:02:12', '2018-12-31 23:58:00', ['sec' => 7452]],
    ];
  }
}