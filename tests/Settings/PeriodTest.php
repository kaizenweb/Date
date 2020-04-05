<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class PeriodTest extends BaseTest {

  /**
   * @dataProvider validEnforceSettingsProvider
   */
  public function testShouldEnforce(array $settings):void {
    $settings = ['period' => $settings];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->addPeriod());
  }

  public function validEnforceSettingsProvider():array {
    return [
      'seconds' => [['sec' => 44, 'secs' => 3, 'second' => 600, 'seconds' => 311,]],
      'minutes' => [['min' => 3, 'mins' => 35, 'minute' => 15, 'minutes' => 60,]],
      'hours' => [['hour' => 2, 'hours' => 24]],
      'days' => [['day' => 23, 'days' => 3]],
      'weeks' => [['week' => 23, 'weeks' => 2]],
      'months' => [['month' => 3, 'months' => 12]],
      'years' => [['year' => 5, 'years' => 1]],
      'modify' => [['modify' => 'last day of this month']],
      'combination' => [['sec' => 4, 'minutes' => 20, 'hour' => 1, 'month' => 4]]
    ];
  }

  public function testShouldNotEnforce():void {
    $settings = [];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->addPeriod());
  }

  /**
   * @dataProvider validEnforceSettingsProvider
   */
  public function testSettings(array $settings):void {
    $settings = ['period' => $settings];

    $class = $this->_getSettings($settings, null);

    $this->assertEquals($settings['period'], $class->getPeriod());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors(array $settings):void {
    $settings = ['period' => $settings];

    $this->_settingsValidatorException($settings, null);
  }

  public function invalidSettingsProvider():array {
    return [
      'invalid hour' => [['hours' => 'eee',]],
      'invalid min' => [['minutes' => '12',]],
      'invalid day' => [['day' => new \DateTime(),]],
      'invalid combination' => [['min' => 22, 'days' => 3, 'year' => '1']],
      'invalid modify' => [['modify' => 10,]],
      'negative min' => [['min' => -22, 'days' => 3, 'year' => 1]],
    ];
  }
}