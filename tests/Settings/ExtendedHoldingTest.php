<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

class ExtendedHoldingTest extends BaseTest {

  public function testShouldEnforce():void {
    $settings = [
      'extended_holding' => [
        'monday' => 3,
        'tuesday' => 2,
        'wednesday' => 1,
        'thursday' => 0,
        'friday' => 6,
        'saturday' => 5,
        'sunday' => 4,
      ],
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertTrue($class->enforceExtendedHoldingPeriod());
  }

  public function testShouldNotEnforce():void {
    $settings = [];

    $class = $this->_getSettings($settings, null);

    $this->assertFalse($class->enforceExtendedHoldingPeriod());
  }

  public function testSettings():void {
    $settings = [
      'extended_holding' => [
        'monday' => 3,
        'tuesday' => 2,
        'wednesday' => 1,
        'thursday' => 0,
        'friday' => 6,
        'saturday' => 5,
        'sunday' => 4,
      ],
    ];

    $class = $this->_getSettings($settings, null);

    $this->assertEquals($settings['extended_holding'], $class->getExtendedHoldingPeriod());
  }

  /**
   * @dataProvider invalidSettingsProvider
   */
  public function testSettingErrors(array $settings):void {
    $settings = ['extended_holding' => $settings];

    $this->_settingsValidatorException($settings, null);
  }

  public function invalidSettingsProvider():array {
    return [
      'invalid day' => [['monday' => 3, 'tuesday' => 22, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,]],
      'invalid day negative' => [['monday' => 3, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => -4,]],
      'invalid multiple sa,e day' => [['monday' => 2, 'tuesday' => 2, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,]],
      'invalid day name' => [['monday' => 3, 'tuesday' => 2, 'onsdag' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,]],
      'missing a day' => [['monday' => 3, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,]],
      'a day too many' => [['monday' => 3, 'tuesday' => 2, 'onsdag' => 4, 'wednesday' => 1, 'thursday' => 0, 'friday' => 6, 'saturday' => 5, 'sunday' => 4,]],
    ];
  }
}