<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Value\Settings;

use Kaizen\Date\Value\Settings;
use PHPUnit\Framework\TestCase;

class ImmutableTest extends TestCase {

  public function testVerifyThatItIsImmutable():void {
    $settings = new Settings('{}', null);

    $this->expectException(\BadMethodCallException::class);

    $settings->__construct('{}', null);
  }
}