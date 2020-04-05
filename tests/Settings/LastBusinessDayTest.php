<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class LastBusinessDayTest extends BaseTest {

  public function testShouldEnforce():void {
    $settings = [
      'last_business_day' => true
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->enforceLastBusinessDayOfMonth());
  }

  public function testShouldNotEnforce():void {
    $settings = [
      'last_business_day' => false
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceLastBusinessDayOfMonth());

    $settings = [];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceLastBusinessDayOfMonth());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors($value):void {
    $settings = ['last_business_day' => $value];

    $this->_settingsValidatorException($settings, null);
  }

  public function invalidSettingsProvider():array {
    return [
      'passed integer one' => [1],
      'passed object' => [new \DateTime(),],
      'passed string one' => ['1',],
      'passed integer zero' => [0,],
      'passed string' => ['s',],
    ];
  }
}