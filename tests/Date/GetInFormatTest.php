<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class GetInFormatTest extends TestCase {

  /**
   * @dataProvider validResultProvider
   */
  public function testResult(string $startDate, string $expected, string $format, ?string $accountTimezone):void {
    $date = new Date($startDate, 'Europe/Oslo');

    if ($accountTimezone !== null) {
      $date->addAccountTimezone($accountTimezone, 'NO');
    }

    $this->assertEquals($expected, $date->getInFormat($format));
  }

  public function validResultProvider():array {
    return [
      'date'              => ['2012-05-16 10:28:00', '2012-05-16', 'date', null],
      'yearmonth'         => ['2012-05-16 10:28:00', '2012-05', 'yearmonth', null],
      'datetime'          => ['2012-05-16 10:28:00', '2012-05-16 10:28:00', 'datetime', null],
      'creditcard'        => ['2012-05-16 10:28:00', '2012-05-15', 'creditcard', null],
      'F j, Y, g:i a'     => ['2012-05-16 10:28:00', 'May 16, 2012, 10:28 am', 'F j, Y, g:i a', null],
      'ISO 8601'          => ['2012-05-16 10:28:00', '2012-05-16T10:28:00+02:00', 'iso8601', null],

      //Repeat with account timezone
      'date v2'           => ['2012-05-16 10:28:00', '2012-05-16', 'date', 'Europe/Sofia'],
      'yearmonth v2'      => ['2012-05-16 10:28:00', '2012-05', 'yearmonth', 'Pacific/Auckland'],
      'datetime v2'       => ['2012-05-16 10:28:00', '2012-05-16 20:28:00', 'datetime', 'Pacific/Auckland'],
      'creditcard v2'     => ['2012-05-16 10:28:00', '2012-05-15', 'creditcard', 'Pacific/Auckland'],
      'F j, Y, g:i a v2'  => ['2012-05-16 10:28:00', 'May 16, 2012, 11:28 am', 'F j, Y, g:i a', 'Europe/Sofia'],
      'ISO 8601 v2'       => ['2012-05-16 10:28:00', '2012-05-16T11:28:00+03:00', 'iso8601', 'Europe/Sofia'],
    ];
  }
}