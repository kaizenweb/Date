<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class RemoveTimeWeeksTest extends TestCase {

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
      'week'                => ['2018-05-15 06:48:00', '2018-05-08 06:48:00', ['week' => 1]],
      'week many'           => ['2018-07-03 06:48:00', '2018-05-08 06:48:00', ['week' => 8]],
      'week leap year'       => ['2016-03-01 10:28:00', '2016-02-16 10:28:00', ['week' => 2]],
      'week non leap year'   => ['2017-03-02 10:28:00', '2017-02-16 10:28:00', ['week' => 2]],
      'week year'           => ['2019-05-07 10:28:00', '2018-05-08 10:28:00', ['week' => 52]],
      'week year leap year'  => ['2016-05-06 10:28:00', '2015-05-08 10:28:00', ['week' => 52]],
    ];
  }
}