<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class PeriodicIntervalTest extends BaseTest {

  /**
   * @dataProvider validEnforceSettingsProvider
   */
  public function testShouldEnforce(array $settings):void {
    $settings = ['periodic_interval' => $settings];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->enforcePeriodicInterval());
  }

  public function validEnforceSettingsProvider():array {
    return [
      'all filled out' => [['before' => ['hour' => 3, 'min' => 15], 'every' => 'hour',]],
      'without hour' => [['before' => ['min' => 30], 'every' => 'day',]],
      'without min' => [['before' => ['hour' => 15], 'every' => 'month',]],
      'without before' => [['every' => 'hour',]],
    ];
  }

  public function testShouldNotEnforce():void {
    $settings = [];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforcePeriodicInterval());
  }

  /**
   * @dataProvider validEnforceSettingsProvider
   */
  public function testSettings(array $settings):void {
    $settings = ['periodic_interval' => $settings];

    $class = $this->_getSettings($settings, null);

    $this->assertEquals($settings['periodic_interval'], $class->getPeriodicInterval());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors(array $settings):void {
    $settings = ['periodic_interval' => $settings];

    $this->_settingsValidatorException($settings, null);
  }

  public function invalidSettingsProvider():array {
    return [
      'invalid hour' => [['before' => ['min' => 63], 'every' => 'day',]],
      'invalid min' => [['before' => ['hour' => 40], 'every' => 'month',]],
      'invalid every' => [['before' => ['hour' => 16], 'every' => 'dag',]],
      'invalid every only' => [['every' => 'dag',]],
      'without every' => [['before' => ['hour' => 10],]],
    ];
  }
}