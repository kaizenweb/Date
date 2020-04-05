<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class CutoffHourTest extends BaseTest {

  public function testShouldEnforce():void {
    $date = new \DateTime('2012-05-16 10:28:00', new \DateTimeZone('Europe/Oslo'));

    $settings = [
      'cutoff_hour' => [
        'hour' => 15,
        'day' => 3,
      ],
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->enforceCutoffHour($date));

    $settings = [
      'cutoff_hour' => [
        'hour' => 2
      ],
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->enforceCutoffHour($date));
  }

  public function testShouldNotEnforce():void {
    $date = new \DateTime('2012-05-16 10:28:00', new \DateTimeZone('Europe/Oslo'));

    $settings = [
      'cutoff_hour' => [
        'hour' => 15,
        'day' => 4,
      ],
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceCutoffHour($date));
  }

  public function testSettings():void {
    $settings = [
      'cutoff_hour' => [
        'hour' => 18,
        'day' => 3,
      ],
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertEquals($settings['cutoff_hour']['hour'], $class->getCutoffHour());

    $settings = [
      'cutoff_hour' => [
        'hour' => 5,
      ],
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertEquals($settings['cutoff_hour']['hour'], $class->getCutoffHour());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors(array $value):void {
    $settings = ['cutoff_hour' => $value];

    $this->_settingsValidatorException($settings, null);
  }

  public function invalidSettingsProvider():array {
    return [
      'invalid hour' => [['hour' => 32, 'day' => 4,]],
      'invalid day' => [['hour' => 13, 'day' => 33,]],
      'missing hour' => [['hour' => null, 'day' => 4,]],
    ];
  }
}