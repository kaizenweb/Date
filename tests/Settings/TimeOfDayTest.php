<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class TimeOfDayTest extends BaseTest {

  public function testShouldEnforce():void {
    $settings = [
      'time_of_day' => [
        'hour' => 8,
        'min' => 16,
      ],
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->enforceTimeOfDay());
  }

  public function testShouldNotEnforce():void {
    $settings = [];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceTimeOfDay());
  }

  public function testSettings():void {
    $settings = [
      'time_of_day' => [
        'hour' => 2,
        'min' => 15,
      ],
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertEquals($settings['time_of_day'], $class->getTimeOfDay());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors(array $value):void {
    $settings = ['time_of_day' => $value];

    $this->_settingsValidatorException($settings, null);
  }

  public function invalidSettingsProvider():array {
    return [
      'invalid hour' => [['hour' => 32, 'min' => 4,]],
      'invalid min' => [['hour' => 13, 'min' => 87,]],
      'missing hour' => [['hour' => null, 'min' => 4,]],
      'missing min' => [['hour' => 6, 'min' => null,]],
    ];
  }
}