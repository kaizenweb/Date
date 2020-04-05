<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class TimezoneTest extends BaseTest {

  /**
   * @dataProvider validEnforceSettingsProvider
   */
  public function testShouldEnforce(string $timezone):void {
    $class = $this->_getSettings([], $timezone);

    $this->assertTrue($class->enforceSwitchTimezone(new \DateTimeZone('Europe/Oslo')));
  }

  public function validEnforceSettingsProvider():array {
    return [
      'UTC' => ['UTC',],
      'USA' => ['America/New_York',],
      'Australia' => ['Pacific/Auckland'],
      'Europe +1' => ['Europe/Sofia']
    ];
  }

  public function testShouldNotEnforce():void {
    $class = $this->_getSettings([], null);

    $this->assertFalse($class->enforceSwitchTimezone(new \DateTimeZone('Europe/Oslo')));

    $class = $this->_getSettings([], 'Europe/Paris');

    $this->assertFalse($class->enforceSwitchTimezone(new \DateTimeZone('Europe/Oslo')));
  }

  /**
   * @dataProvider validEnforceSettingsProvider
   */
  public function testSettings(string $timezone):void {
    $class = $this->_getSettings([], $timezone);

    $this->assertEquals(new \DateTimeZone($timezone), $class->getTimezone());

    $class = $this->_getSettings([], null);

    $this->assertNull($class->getTimezone());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors(string $timezone):void {
    $this->_settingsValidatorException([], $timezone);
  }

  public function invalidSettingsProvider():array {
    return [
      'random string' => ['test'],
      'invalid timezone' => ['Europe/Oslo2'],
      'random number as string' => ['1'],
    ];
  }
}