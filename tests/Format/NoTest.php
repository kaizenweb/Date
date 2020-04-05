<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Format;

class NoTest extends BaseTest {

  protected $_class = 'NO';

  public function formatProvider():array {
    return [
      'format' => ['getFormat', 'NO'],
      'hour' =>   ['getHour', 'G:i'],
      'basicdate' =>   ['getBasicDate', 'j/n/Y'],
      'date' => ['getDate', 'D, j F Y'],
    ];
  }
}