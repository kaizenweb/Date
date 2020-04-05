<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Format;

class UsTest extends BaseTest {

  protected $_class = 'US';

  public function formatProvider():array {
    return [
      'format' => ['getFormat', 'US'],
      'hour' =>   ['getHour', 'G:i a'],
      'basicdate' =>   ['getBasicDate', 'n/j/Y'],
      'date' => ['getDate', 'D, F jS, Y'],
    ];
  }
}