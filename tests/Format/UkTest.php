<?php
declare(strict_types = 1);

namespace Tests\Kaizen\Date\Format;

class UkTest extends BaseTest {

  protected $_class = 'UK';

  public function formatProvider():array {
    return [
      'format' => ['getFormat', 'UK'],
      'hour' =>   ['getHour', 'G:i a'],
      'basicdate' =>   ['getBasicDate', 'j/n/Y'],
      'date' => ['getDate', 'D, jS F Y'],
    ];
  }
}