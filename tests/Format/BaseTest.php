<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Format;

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase {

  protected $_class;

  /**
   * @dataProvider formatProvider
   */
  public function testFormats(string $method, string $expected):void {
    $namespace = '\\Kaizen\\Date\\Format\\'.$this->_class;

    $format = new $namespace();

    $this->assertEquals($expected, $format->$method());
  }

  abstract public function formatProvider();
}