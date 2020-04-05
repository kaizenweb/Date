<?php
/**
 * Kaizen-Framework: Date
 *
 * @company    Kaizen Web-Productions (http://www.kaizen-web.com)
 * @version    1.0
 * @copyright  Copyright(C), Kaizen Web-Productions, 2004-2020, All Rights Reserved.
 * @package    KaizenFramework
 * @subpackage Date
 */

namespace Kaizen\Date\Format;

/**
 * United States of America Date Format System
 *
 * Displays the date and time according to their standards.
 *
 * @author     Sven Arild Helleland
 * @company    Kaizen Web-Productions (http://www.kaizen-web.com)
 * @version    1.0
 * @copyright  Copyright(C), Kaizen Web-Productions, 2004-2020, All Rights Reserved.
 * @package Format
 * @subpackage US
 */
class US extends Base {

  /**
   * Get Hour
   *
   * @return string
   */
  public function getHour():string {
    return 'G:i a';
  }


  /**
   * Get Basic Date
   *
   * @return string
   */
  public function getBasicDate():string {
    return 'n/j/Y';
  }


  /**
   * Get Date
   *
   * @return string
   */
  public function getDate():string {
    return 'D, F jS, Y';
  }
}