<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class NotEndOnWeekendTest extends BaseTest {

  public function testShouldEnforce():void {
    $settings = [
      'not_end_on_weekend' => true
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->enforceNotEndOnWeekend());
  }

  public function testShouldNotEnforce():void {
    $settings = [
      'not_end_on_weekend' => false
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceNotEndOnWeekend());

    $settings = [];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceNotEndOnWeekend());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors($value):void {
    $settings = ['not_end_on_weekend' => $value];

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