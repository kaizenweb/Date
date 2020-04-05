<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class JsonSerializeTest extends TestCase {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, array $expected, ?string $accountTimezone):void {
    $date = new Date($startDate, 'Europe/Oslo');

    if ($accountTimezone !== null) {
      $date->addAccountTimezone($accountTimezone, 'NO');
    }

    $this->assertEquals(json_encode($expected), json_encode($date));
  }

  public function validResultProvider():array {
    return [
      'base'     => ['2018-05-08 06:48:00', ['class' => 'Date', 'date' => '2018-05-08 06:48:00', 'tz' => 'Europe/Oslo'], null],
      'timezone' => ['2018-05-08 06:48:00', ['class' => 'Date', 'date' => '2018-05-08 06:48:00', 'tz' => 'Europe/Oslo', 'acc_tz' => 'Pacific/Auckland', 'acc_format' => 'NO'], 'Pacific/Auckland'],
    ];
  }
}