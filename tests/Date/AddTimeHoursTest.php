<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class AddTimeHoursTest extends TestCase {

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
      'hour'           => ['2012-05-16 10:28:00', '2012-05-16 13:28:00', ['hour' => 3]],
      'hour some'      => ['2012-05-16 10:28:00', '2012-05-16 22:28:00', ['hour' => 12]],
      'hour midnight'  => ['2012-05-16 22:28:29', '2012-05-17 00:28:29', ['hour' => 2]],
      'hour month'     => ['2012-05-31 22:28:57', '2012-07-01 04:28:57', ['hour' => 726]],
      'hour year'      => ['2012-12-29 10:28:12', '2013-01-02 10:28:12', ['hour' => 96]],
    ];
  }
}