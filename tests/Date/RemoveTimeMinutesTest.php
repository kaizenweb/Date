<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class RemoveTimeMinutesTest extends TestCase {

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
      'min'           => ['2012-05-16 10:31:00', '2012-05-16 10:28:00', ['min' => 3]],
      'min some'      => ['2012-05-16 11:57:00', '2012-05-16 10:28:00', ['min' => 89]],
      'min midnight'  => ['2012-05-17 02:57:29', '2012-05-16 10:28:29', ['min' => 989]],
      'min month'     => ['2012-06-01 06:25:57', '2012-05-31 22:28:57', ['min' => 477]],
      'min year'      => ['2013-01-03 14:40:12', '2012-12-29 10:28:12', ['min' => 7452]],
    ];
  }
}