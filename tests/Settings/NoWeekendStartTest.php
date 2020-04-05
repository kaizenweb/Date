<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class NoWeekendStartTest extends BaseTest {

  public function testShouldEnforce():void {
    $settings = [
      'no_weekend_start' => true
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->enforceNoWeekendStart());
  }

  public function testShouldNotEnforce():void {
    $settings = [
      'no_weekend_start' => false
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceNoWeekendStart());

    $settings = [];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceNoWeekendStart());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors($value):void {
    $settings = ['no_weekend_start' => $value];

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