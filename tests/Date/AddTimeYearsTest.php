<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class AddTimeYearsTest extends TestCase {

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
      'year'                => ['2018-05-08 06:48:00', '2020-05-08 06:48:00', ['year' => 2]],
      'year leap'           => ['2016-01-08 06:48:00', '2017-01-08 06:48:00', ['year' => 1]],
      'year from leap'      => ['2016-02-29 06:48:00', '2017-02-28 06:48:00', ['year' => 1]],
      'year from leap v2'   => ['2016-02-28 06:48:00', '2017-02-27 06:48:00', ['year' => 1]],
      'year from leap v3'   => ['2016-02-27 06:48:00', '2017-02-26 06:48:00', ['year' => 1]],
      'year from leap v4'   => ['2016-02-26 06:48:00', '2017-02-26 06:48:00', ['year' => 1]],
      'year to leap'        => ['2015-02-28 06:48:00', '2016-02-29 06:48:00', ['year' => 1]],
      'year to leap v2'     => ['2015-02-27 06:48:00', '2016-02-28 06:48:00', ['year' => 1]],
      'year to leap v3'     => ['2015-02-26 06:48:00', '2016-02-27 06:48:00', ['year' => 1]],
      'year many'           => ['2018-05-08 06:48:00', '2030-05-08 06:48:00', ['year' => 12]],
    ];
  }
}