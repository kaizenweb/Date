<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class AddTimeModifyTest extends TestCase {

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
      'min'           => ['2018-05-08 06:48:00', '2018-05-08 06:51:00', ['modify' => '+3 min']],
      'min many'      => ['2018-05-08 06:48:00', '2018-05-08 08:17:00', ['modify' => '+89 min']],
      'hour'          => ['2018-05-08 06:48:00', '2018-05-08 09:48:00', ['modify' => '+3 hours']],
      'next sunday'   => ['2018-05-08 06:48:00', '2018-05-13 00:00:00', ['modify' => 'next sunday']],
      'last monday'   => ['2018-05-08 06:48:00', '2018-05-07 00:00:00', ['modify' => 'last monday']],
      'last tuesday'  => ['2018-05-08 06:48:00', '2018-05-01 12:00:00', ['modify' => 'last tuesday noon']],
      'next month'    => ['2018-05-08 06:48:00', '2018-06-08 06:48:00', ['modify' => 'next month']],
      'next year'     => ['2018-05-08 06:48:00', '2019-02-08 06:48:00', ['modify' => 'next year feb']],
    ];
  }
}