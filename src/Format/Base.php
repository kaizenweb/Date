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
 * Abstract Date Format System
 *
 * Allows the user to select the desired format to show the date and time on the website.
 *
 * @author     Sven Arild Helleland
 * @company    Kaizen Web-Productions (http://www.kaizen-web.com)
 * @version    1.0
 * @copyright  Copyright(C), Kaizen Web-Productions, 2004-2020, All Rights Reserved.
 * @package Format
 * @subpackage Base
 */
abstract class Base {

  /**
   * Get Format
   *
   * Returns the format the user has selected.
   *
   * @return string
   */
  public function getFormat():string {
    return str_replace('Kaizen\\Date\\Format\\', '', get_called_class());
  }


  /**
   * Get Hour
   *
   * @return string
   */
  abstract public function getHour():string;


  /**
   * Get Basic Date
   *
   * @return string
   */
  abstract public function getBasicDate():string;


  /**
   * Get Date
   *
   * @return string
   */
  abstract public function getDate():string;
}