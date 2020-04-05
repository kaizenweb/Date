<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class RemoveTimeModifyTest extends TestCase {

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
      'min'           => ['2018-05-08 06:48:00', '2018-05-08 06:45:00', ['modify' => '-3 min']],
      'min many'      => ['2018-05-08 06:48:00', '2018-05-08 05:19:00', ['modify' => '-89 min']],
      'hour'          => ['2018-05-08 06:48:00', '2018-05-08 03:48:00', ['modify' => '-3 hours']],
      'next sunday'   => ['2018-05-08 06:48:00', '2018-05-13 00:00:00', ['modify' => 'next sunday']],
      'last monday'   => ['2018-05-08 06:48:00', '2018-05-07 00:00:00', ['modify' => 'last monday']],
      'last tuesday'  => ['2018-05-08 06:48:00', '2018-05-01 12:00:00', ['modify' => 'last tuesday noon']],
      'last month'    => ['2018-05-08 06:48:00', '2018-04-08 06:48:00', ['modify' => 'last month']],
      'last year'     => ['2018-05-08 06:48:00', '2017-02-08 06:48:00', ['modify' => 'last year feb']],
    ];
  }
}