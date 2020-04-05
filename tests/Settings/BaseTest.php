<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase {

  protected function _getSettings(array $settings, ?string $timezone):\Kaizen\Date\Value\Settings {
    return new \Kaizen\Date\Value\Settings(json_encode($settings, JSON_THROW_ON_ERROR), $timezone);
  }

  protected function _settingsValidatorException(array $settings, ?string $timezone):void {
    $this->expectException(\TypeError::class);

    (new \Kaizen\Date\Value\Settings(json_encode($settings, JSON_THROW_ON_ERROR), $timezone));
  }
}