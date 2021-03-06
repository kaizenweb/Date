<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date;

use Kaizen\Date;
use PHPUnit\Framework\TestCase;

class RemoveTimeMonthsTest extends TestCase {

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
      'month'                     => ['2018-06-08 06:48:00', '2018-05-08 06:48:00', ['month' => 1]],
      'month 29'                  => ['2018-06-29 06:48:00', '2018-05-30 06:48:00', ['month' => 1]], //June 29 = May 30, as both are second last day of month
      'month 30'                  => ['2018-07-31 06:48:00', '2018-06-30 06:48:00', ['month' => 1]], //July 31 = June 30, as both are last day of month
      'month 30 v2'               => ['2018-07-30 06:48:00', '2018-06-29 06:48:00', ['month' => 1]], //July 30 = June 29, as both are second last day of month
      'month 31'                  => ['2018-07-31 06:48:00', '2018-05-31 06:48:00', ['month' => 2]],
      'month 30 to 31'            => ['2018-06-30 06:48:00', '2018-05-31 06:48:00', ['month' => 1]], //June 30 - May 31, as both are last day of month
      'month feb from'            => ['2016-02-28 06:48:00', '2016-01-30 06:48:00', ['month' => 1]], //Leap Feb 28 = Jan 30, as both are second last day of month
      'month feb leap from'       => ['2016-02-29 06:48:00', '2016-01-31 06:48:00', ['month' => 1]],
      'month march to feb'        => ['2018-03-31 06:48:00', '2018-02-28 06:48:00', ['month' => 1]],
      'month march to feb2'       => ['2018-03-30 06:48:00', '2018-02-27 06:48:00', ['month' => 1]],
      'month march to feb3'       => ['2018-03-29 06:48:00', '2018-02-26 06:48:00', ['month' => 1]], //March 29 = Feb 26, as both are third last day of month
      'month march to feb4'       => ['2018-03-28 06:48:00', '2018-02-26 06:48:00', ['month' => 1]], //March 28 = Feb 26, to avoid that the next subscription will be at the end of month
      'month march to feb leap'   => ['2016-03-31 06:48:00', '2016-02-29 06:48:00', ['month' => 1]],
      'month march to feb leap2'  => ['2016-03-30 06:48:00', '2016-02-28 06:48:00', ['month' => 1]],
      'month march to feb leap3'  => ['2016-03-29 06:48:00', '2016-02-27 06:48:00', ['month' => 1]],
      'month many'                => ['2019-01-08 06:48:00', '2018-05-08 06:48:00', ['month' => 8]],
      'month leap year'           => ['2016-04-16 10:28:00', '2016-02-16 10:28:00', ['month' => 2]],
      'month non leap year'       => ['2017-04-16 10:28:00', '2017-02-16 10:28:00', ['month' => 2]],
      'month year'                => ['2019-05-08 10:28:00', '2018-05-08 10:28:00', ['month' => 12]],
      'month year leap year'      => ['2016-05-08 10:28:00', '2015-05-08 10:28:00', ['month' => 12]],
      'month years'               => ['2018-09-08 10:28:00', '2015-05-08 10:28:00', ['month' => 40]],

      //Go through a year, testing each month removing one, from last day
      'month jan'               => ['2018-02-28 06:48:00', '2018-01-31 06:48:00', ['month' => 1]],
      'month feb'               => ['2018-03-31 06:48:00', '2018-02-28 06:48:00', ['month' => 1]],
      'month march'             => ['2018-04-30 06:48:00', '2018-03-31 06:48:00', ['month' => 1]],
      'month april'             => ['2018-05-31 06:48:00', '2018-04-30 06:48:00', ['month' => 1]],
      'month may'               => ['2018-06-30 06:48:00', '2018-05-31 06:48:00', ['month' => 1]],
      'month june'              => ['2018-07-31 06:48:00', '2018-06-30 06:48:00', ['month' => 1]],
      'month july'              => ['2018-08-31 06:48:00', '2018-07-31 06:48:00', ['month' => 1]],
      'month aug'               => ['2018-09-30 06:48:00', '2018-08-31 06:48:00', ['month' => 1]],
      'month sept'              => ['2018-10-31 06:48:00', '2018-09-30 06:48:00', ['month' => 1]],
      'month oct'               => ['2018-11-30 06:48:00', '2018-10-31 06:48:00', ['month' => 1]],
      'month nov'               => ['2018-12-31 06:48:00', '2018-11-30 06:48:00', ['month' => 1]],
      'month dec'               => ['2019-01-31 06:48:00', '2018-12-31 06:48:00', ['month' => 1]],

      'month jan leap'          => ['2016-02-29 06:48:00', '2016-01-31 06:48:00', ['month' => 1]],
      'month feb leap'          => ['2016-03-31 06:48:00', '2016-02-29 06:48:00', ['month' => 1]],

      //Go through a year, testing each month removing one, from second last day
      'month jan v2'            => ['2018-02-27 06:48:00', '2018-01-30 06:48:00', ['month' => 1]],
      'month feb v2'            => ['2018-03-30 06:48:00', '2018-02-27 06:48:00', ['month' => 1]],
      'month march v2'          => ['2018-04-29 06:48:00', '2018-03-30 06:48:00', ['month' => 1]],
      'month april v2'          => ['2018-05-30 06:48:00', '2018-04-29 06:48:00', ['month' => 1]],
      'month may v2'            => ['2018-06-29 06:48:00', '2018-05-30 06:48:00', ['month' => 1]],
      'month june v2'           => ['2018-07-30 06:48:00', '2018-06-29 06:48:00', ['month' => 1]],
      'month july v2'           => ['2018-08-30 06:48:00', '2018-07-30 06:48:00', ['month' => 1]],
      'month aug v2'            => ['2018-09-29 06:48:00', '2018-08-30 06:48:00', ['month' => 1]],
      'month sept v2'           => ['2018-10-30 06:48:00', '2018-09-29 06:48:00', ['month' => 1]],
      'month oct v2'            => ['2018-11-29 06:48:00', '2018-10-30 06:48:00', ['month' => 1]],
      'month nov v2'            => ['2018-12-30 06:48:00', '2018-11-29 06:48:00', ['month' => 1]],
      'month dec v2'            => ['2019-01-30 06:48:00', '2018-12-30 06:48:00', ['month' => 1]],

      'month jan leap v2'       => ['2016-02-28 06:48:00', '2016-01-30 06:48:00', ['month' => 1]],
      'month feb leap v2'       => ['2016-03-30 06:48:00', '2016-02-28 06:48:00', ['month' => 1]],
    ];
  }
}