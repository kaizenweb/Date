<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class DayOfMonthTest extends BaseTest {

  public function testShouldEnforce():void {
    $settings = [
      'day_of_month' => 8
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->enforceDayOfMonth());
  }

  public function testShouldNotEnforce():void {
    $settings = [];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceDayOfMonth());
  }

  public function testSettings():void {
    $settings = [
      'day_of_month' => 16
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertEquals($settings['day_of_month'], $class->getDayOfMonth());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors($day):void {
    $settings = ['day_of_month' => $day];

    $this->_settingsValidatorException($settings, null);
  }

  public function invalidSettingsProvider():array {
    return [
      'invalid day' => [35],
      'invalid day negative' => [-5],
    ];
  }
}