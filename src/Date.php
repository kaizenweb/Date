<?php
/**
 * Kaizen-Framework: Date
 *
 * @company    Kaizen Web-Productions (http://www.kaizen-web.com)
 * @version    1.0
 * @copyright  Copyright(C), Kaizen Web-Productions, 2004-2020, All Rights Reserved.
 * @package    KaizenFramework
 */

declare(strict_types = 1);

namespace Kaizen;

use \Kaizen\Date\Format as Format;
use \Kaizen\Date\Value\Settings;

/**
 * Immutable Date System
 *
 * Allow the creation of date object, with system timezone, account timezone and account format passed along.
 *
 * It will automatically convert the date to the account timezone and format if it has been set,
 * making it effortless to show the information in the timezone of the account owner.
 *
 * Note. The account timezone and format are optional.
 *
 * Remember: THE DATE IS IMMUTABLE!
 *
 * @author     Sven Arild Helleland
 * @company    Kaizen Web-Productions (http://www.kaizen-web.com)
 * @version    1.0
 * @copyright  Copyright(C), Kaizen Web-Productions, 2004-2020, All Rights Reserved.
 * @package    KaizenFramework
 * @subpackage Date
 */
final class Date implements \JsonSerializable {

  /**
   * Immutable
   *
   * @var bool
   */
  private $_mutable = true;

  /**
   * System TimeZone
   *
   * @var \DateTimeZone
   */
  private $_system_timezone;

  /**
   * System Default Format Rules
   *
   * @var string
   */
  private $_system_default_format = 'US';

  /**
   * Account TimeZone
   *
   * @var \DateTimeZone
   */
  private $_account_timezone;

  /**
   * Account Date Format Rules
   *
   * @var Format\Base
   */
  private $_account_format;

  /**
   * Date
   *
   * Will always be using the system timezone, if the account timezone is set we clone and convert it before
   * returning the date information.
   *
   * @var \DateTime
   */
  private $_date;


  /**
   * Constructor
   *
   * @param null|string $date
   * @param string $timezone
   *
   * @throws \Exception
   */
  public function __construct(?string $date, string $timezone) {

    if ($this->_mutable === false) {
      throw new \BadMethodCallException('Attempt to use immutable value object as mutable.');
    }

    $this->_system_timezone = new \DateTimeZone($timezone);

    if (empty($date)) { //Passed no information
      $this->_date = new \DateTime('now', $this->_system_timezone);
    }
    elseif ($date[0] == '@') { //Passed Unix Timestamp
      $this->_date = new \DateTime($date);
      $this->_date->setTimezone($this->_system_timezone);
    }
    elseif (strpos($date, '+') !== false && strlen($date) == 25) { //Passed date specified timezone
      $this->_date = new \DateTime($date);
      $this->_date->setTimezone($this->_system_timezone);
    }
    else {
      $this->_date = new \DateTime($date, $this->_system_timezone);
    }

    $this->_mutable = false;
  }


  /**
   * JSON Serialize
   *
   * When a Date instance is JSON encoded, it save the information required to recreate it
   *
   * Note: "acc_tz" and "acc_format" is only sent over if they are set!
   *
   * @return array
   */
  public function jsonSerialize():array {
    $data = [
      'class' => 'Date',
      'date' => $this->_date->format('Y-m-d H:i:s'),
      'tz' => $this->_system_timezone->getName()
      ];

    if (!empty($this->_account_timezone)) {
      $data['acc_tz'] = $this->_account_timezone->getName();
    }

    if (!empty($this->_account_format)) {
      $data['acc_format'] = $this->_account_format->getFormat();
    }

    return $data;
  }


  /**
   * To String
   *
   * Returns the date in a human friendly and correct format according to account timezone and format settings.
   *
   * @return string
   */
  public function __toString():string {
    return $this->getAccountFormat('date');
  }


  /**
   * Get DateTime Object
   *
   * @return \DateTime
   */
  public function getDateTime():\DateTime {
    return clone $this->_date;
  }


  /**
   * Get System TimeZone Name
   *
   * @return string
   */
  public function getSystemTimezoneName():string {
    return $this->_system_timezone->getName();
  }


  /**
   * Get Date
   *
   * Makes it quick to copy system timezone and also account timezone/details directly to a new date.
   *
   * NOTE: It is important to remember that it is the returned date object that has the new date!
   *
   * @param string $date
   * @return Date
   *
   * @throws \Exception
   */
  public function getDate(?string $date):Date {
    $temp = clone $this;
    $temp->_getDate($date);

    return $temp;
  }


  /**
   * Get Date
   *
   * @param string $date
   * @return void
   *
   * @throws \Exception
   */
  private function _getDate(?string $date):void {

    if ($date === null) {
      $date = 'now';
    }

    $this->_date = new \DateTime($date, $this->_system_timezone);
  }


  /**
   * Add Account Timezone
   *
   * Allow automatic conversion to the accounts timezone when displaying the date
   *
   * @param string $timezone
   * @param string $dateFormat
   * @return void
   *
   * @throws \Exception
   */
  public function addAccountTimezone(string $timezone, string $dateFormat):void {
    $timezone = new \DateTimeZone($timezone);
    $account_timezone = new \DateTime('2018-05-08 06:48:00', $timezone);
    $system_timezone = new \DateTime('2018-05-08 06:48:00', $this->_system_timezone);

    //If the account and system timezone is the same, no need to apply the account timezone!
    if ($account_timezone != $system_timezone) {
      $this->_account_timezone = $timezone;
    }

    try {
      $namespace = '\\Kaizen\\Date\\Format\\'.$dateFormat;

      $this->_account_format = new $namespace();
    } catch (\Exception $e) {
      //Do nothing, if the account date format is required, it will be setup using default format
    }
  }


  /**
   * Remove Account Timezone
   *
   * Useful when the system its passed along to does not care about the settings for the user
   *
   * NOTE: It is important to remember that it is the returned date object that has the account timezone removed!
   *
   * @return Date
   */
  public function removeAccountTimezone():Date {
    $temp = clone $this;
    $temp->_account_format = null;
    $temp->_account_timezone = null;

    return $temp;
  }


  /**
   * Get Account Format Object
   *
   * If one has not been set, it will create one using the default format
   *
   * @return Format\Base
   */
  private function _getAccountFormat():Format\Base {

    if (empty($this->_account_format)) {
      $namespace = '\\Kaizen\\Date\\Format\\'.$this->_system_default_format;

      $this->_account_format = $namespace();
    }

    return $this->_account_format;
  }


  /**
   * Switch To Account Timezone
   *
   * @return \DateTime
   */
  private function _switchToAccountTimezone():\DateTime {

    if ($this->_account_timezone === null) {
      return $this->_date;
    }

    $temp = clone $this->_date;
    $temp->setTimezone($this->_account_timezone);

    return $temp;
  }


  /**
   * Get Date In Account Format
   *
   * The method should be utilized to show the formatted date on the frontend.
   *
   * @param string $format
   * @return string
   */
  public function getAccountFormat(string $format):string {
    $date = $this->_switchToAccountTimezone();

    switch ($format) {
      case 'hour':
        $string = $this->_getAccountFormat()->getHour();
        break 1;
      case 'basicdate':
        $string = $this->_getAccountFormat()->getBasicDate();
        break 1;
      case 'date':
      default:
        $string = $this->_getAccountFormat()->getDate();
    }

    return $date->format($string);
  }


  /**
   * Get Date In Predefined Format
   *
   * Intended for when you want to use one of the predefined formatting for the date.
   *
   * @param string $format
   * @return string
   */
  public function getInFormat(string $format='datetime'):string {
    $date = $this->_switchToAccountTimezone();

    switch ($format) {
      case 'datetime':
        $string = 'Y-m-d H:i:s';
        break 1;
      case 'date':
        $string = 'Y-m-d';
        break 1;
      case 'yearmonth':
        $string = 'Y-m';
        break 1;
      case 'creditcard': //For storing the creditcard expire date in a date column
        $string = 'Y-m-15';
        break 1;
      case 'iso8601':
        $string = 'Y-m-d\TH:i:sP';
        break 1;
      default:
        $string = $format;
    }

    return $date->format($string);
  }


  /**
   * Get Date In Format
   *
   * Intended for when you want to use a custom formatting for the date.
   * In short this makes it easy to read/scan the intent of the code.
   *
   * @param string $format
   * @return string
   */
  public function getFormat(string $format):string {
    return $this->getInFormat($format);
  }


  /**
   * Add Time To A Date Object
   *
   * It is important to note that the method is created for handling subscriptions, and handle month/year different
   * than the default for DateTime. It take in mind the day of the date and make certain it is kept when adding time.
   *
   * Example:
   * 31th May + 1 month = 30th June (and not 1 July)
   * 30th May + 1 month = 29th June (and not 30th June)
   * 30th June + 1 month = 31st July (and not 30th July)
   * 29th June + 1 month = 30th July (and not 29th July)
   * 29th Feb + 1 month = 31st March (and not 29th March) - Leap Year
   * 28th Feb + 1 month = 30st March (and not 28th March) - Leap Year
   * 28th Feb + 1 month = 31st March (and not 28th March)
   * and so on...
   *
   * Information:
   * $update = array([type] => [period]
   *                 , [type] => [period]);
   *
   * The type should be a DateTime supported modifier
   *
   * NOTE:
   * Dealing with months and years, you are only guaranteed correct result by using type, 'month', 'months', 'year' or 'years'!
   *
   * @param array $updateSettings
   * @return Date
   */
  public function addTime(array $updateSettings):Date {
    $temp = clone $this;
    $temp->_date = $temp->_modifyDate(clone $this->_date, $updateSettings);

    return $temp;
  }


  /**
   * Remove Time From A Date Object
   *
   * It is important to note that the method is created for handling subscriptions, and handle month/year different
   * than the default for DateTime. It take in mind the day of the date and make certain it is kept when adding time.
   *
   * Example:
   * 31th July - 1 month = 30th June (and not 1st July)
   * 30th July - 1 month = 29th June (and not 30th June)
   * 30th June - 1 month = 31st May (and not 30th May)
   * 29th June - 1 month = 30th May (and not 29th May)
   * 29th Feb - 1 month = 31st January (and not 29th January) - Leap Year
   * 28th Feb - 1 month = 30st January (and not 28th January) - Leap Year
   * 28th Feb - 1 month = 31st January (and not 28th January)
   * and so on...
   *
   * Information:
   * $update = array([type] => [period]
   *                 , [type] => [period]);
   *
   * The type should be a DateTime supported modifier
   *
   * NOTE:
   * Dealing with months and years, you are only guaranteed correct result by using type, 'month', 'months', 'year' or 'years'!
   *
   * @param array $updateSettings
   * @return Date
   */
  public function removeTime(array $updateSettings):Date {
    $temp = clone $this;
    $temp->_date = $temp->_modifyDate(clone $this->_date, $updateSettings, true);

    return $temp;
  }


  /**
   * Apply Time Settings
   *
   * Changes the time utilizing the settings provided.
   *
   * Look at the Settings file for information about the possible settings available.
   *
   * @param Settings $settings
   * @return Date
   */
  public function applyTimeSettings(Settings $settings):Date {
    $temp = clone $this;
    $temp->_date = $temp->_applyTimeSettings(clone $this->_date, $settings);

    return $temp;
  }


  /**
   * Internal Apply Time Settings
   *
   * @param \DateTime $date
   * @param Settings $settings
   * @return \DateTime
   */
  private function _applyTimeSettings(\DateTime $date, Settings $settings):\DateTime {
    //Should we change to a specific timezone?
    if ($settings->enforceSwitchTimezone($this->_system_timezone) === true) {
      $date->setTimezone($settings->getTimezone());
    }

    //Check if we need to enforce cutoff hour
    if ($settings->enforceCutoffHour($date) === true) {
      $date = $this->_timeOfCutoff($date, $settings->getCutoffHour());
    }

    //Only start calculating from a business day, not on weekend
    if ($settings->enforceNoWeekendStart() === true) {
      $date = $this->_noWeekendSystem($date);
    }

    //Check and see if we should use the extended holding period system
    if ($settings->enforceExtendedHoldingPeriod() === true) {
      $date = $this->_extendedHoldingPeriod($date, $settings->getExtendedHoldingPeriod());
    }

    //Add the holding time
    if ($settings->addPeriod() === true) {
      $date = $this->_modifyDate($date, $settings->getPeriod());
    }

    //Make certain we enforce any periodic interval required
    if ($settings->enforcePeriodicInterval() === true) {
      $date = $this->_periodicInterval($date, $settings->getPeriodicInterval());
    }

    //Check if we should apply any other systems to the holding time
    if ($settings->enforceDayOfMonth() === true) { //Set to specific day of month
      $date = $this->_dayOfMonthSystem($date, $settings->getDayOfMonth());
    }
    elseif ($settings->enforceLastBusinessDayOfMonth() === true) { //Set to last business day of month
      $date = $this->_lastBusinessDayOfMonthSystem($date);
    }

    //Need to be on a business day, not on weekend
    if ($settings->enforceNotEndOnWeekend() === true) {
      $date = $this->_noWeekendSystem($date);
    }

    //If we should change to a specific time (hour/minute)
    if ($settings->enforceTimeOfDay() === true) {
      $date = $this->_timeOfDay($date, $settings->getTimeOfDay());
    }

    //If we changed timezone, switch back
    if ($settings->enforceSwitchTimezone($this->_system_timezone) === true) {
      $date->setTimezone($this->_system_timezone);
    }

    return $date;
  }


  /**
   * Days Difference
   *
   * Note. If you pass a older date the result would be negative value etc.
   * But it only return number not if its positive or negative
   *
   * @param Date $date
   * @return int
   */
  public function daysDifference(Date $date):int {
    $interval = $this->_date->diff($date->getDateTime());

    return (int) $interval->format('%a');
  }


  /**
   * Minutes Difference
   *
   * Note. If you pass a older date the result would be negative value etc.
   * But it only return number not if its positive or negative
   *
   * @param Date $date
   * @return int
   */
  public function minutesDifference(Date $date):int {
    return (int) abs(($this->_date->getTimestamp() - $date->getDateTime()->getTimestamp()) / 60);
  }


  /**
   * Modify Date Object
   *
   * Information:
   * $update = array([type] => [period]
   *                 , [type] => [period]);
   *
   * The type should be a DateTime supported modifier
   *
   * @param \DateTime $date
   * @param array $update
   * @param bool $subtract
   * @return \DateTime
   */
  private function _modifyDate(\DateTime $date, array $update, bool $subtract=false):\DateTime {
    $sign = '+';

    if ($subtract === true) {
      $sign = '-';
    }

    foreach ($update as $type => $period) {

      switch ($type) {
        case 'sec':
        case 'secs':
        case 'second':
        case 'seconds':
        case 'min':
        case 'mins':
        case 'minute':
        case 'minutes':
        case 'hour':
        case 'hours':
        case 'day':
        case 'days':
          $date->modify($sign.$period.' '.$type);
          break 1;
        case 'week':
        case 'weeks':
          $date->modify($sign.$period.' weeks');
          break 1;
        case 'month':
        case 'months':
          $date = $this->_modifyMonth($date, $period, $subtract);
          break 1;
        case 'year':
        case 'years':
          $date = $this->_modifyYear($date, $period, $subtract);
          break 1;
        case 'modify': //Allow us to support all valid Date and Time formats
          if ($date->modify($period) === false) {
            throw new \TypeError('Invalid unit modifier passed along.');
          }

          break 1;
        default:
            throw new \TypeError('The passed modify date option is invalid!');
      }
    }

    return $date;
  }


  /**
   * Modify Years
   *
   * Correctly handle modification of the date object when we are dealing with the end of a month
   *
   * @param \DateTime $oldDate
   * @param int $years
   * @param bool $subtract
   * @return \DateTime
   */
  private function _modifyYear(\DateTime $oldDate, int $years, bool $subtract):\DateTime {
    $months = ($years * 12);

    if ($subtract === true) {
      $date = $this->_removeMonths($oldDate, $months);
    } else {
      $date = $this->_addMonths($oldDate, $months);
    }

    list($old_month, $old_day, $old_leap) = explode('-', $oldDate->format('n-j-L'));
    list($year, $month, $day, $leap) = explode('-', $date->format('Y-n-j-L'));

    if ($old_month == 2 && $day >= 26) {

      if ($old_month == 2 && $old_day == 28) {
        $update_day = 28 - $old_leap + $leap;
      }
      elseif ($old_month == 2 && $old_day == 27) {
        $update_day = 27 - $old_leap + $leap;
      }
      elseif ($old_month == 2 && $old_day == 26) {
        $update_day = 26 + $leap;
      }

      if (!empty($update_day)) {
        $date->setDate((int) $year, (int) $month, (int) $update_day);
      }
    }

    return $date;
  }


  /**
   * Modify Months
   *
   * Correctly handle modification of the date object when we are dealing with the end of a month
   *
   * @param \DateTime $date
   * @param int $months
   * @param bool $subtract
   * @return \DateTime
   */
  private function _modifyMonth(\DateTime $date, int $months, bool $subtract):\DateTime {

    if ($subtract === true) {
      return $this->_removeMonths($date, $months);
    }

    return $this->_addMonths($date, $months);
  }


  /**
   * Add Months
   *
   * Correctly handle modification of the date object when we are dealing with the end of a month
   *
   * @param \DateTime $date
   * @param int $addMonths
   * @return \DateTime
   */
  private function _addMonths(\DateTime $date, int $addMonths):\DateTime {

    if ($addMonths == 0) {
      return $date;
    }

    $temp = clone $date;

    $modifier = "+{$addMonths} months";

    $temp->modify($modifier);

    list($old_month, $old_day, $old_max_days) = explode('-', $date->format('n-j-t'));

    if ((int) $temp->format('m') % 12 != (($addMonths + (int) $old_month) % 12)) {
      $temp->modify("-{$temp->format('d')} days");
    }

    list($year, $month, $day, $max_days, $leap) = explode('-', $temp->format('Y-n-j-t-L'));

    if ($old_month != $month && $day >= 27) {
      $update_day = null;

      if ($old_month == 2 && $old_day == 29) {
        $update_day = $max_days;
      }
      elseif ($old_month == 2 && $old_day == 28) {
        $update_day = $max_days - $leap;
      }
      elseif ($old_month == 2 && $old_day == 27) {
        $update_day = $max_days - (1 + $leap);
      }
      elseif ($old_max_days == 31 && $max_days == 30 && $old_day == 30) {
        $update_day = 29;
      }
      elseif ($old_max_days == 30 && $max_days == 31 && $old_day == 30) {
        $update_day = 31;
      }
      elseif ($old_max_days == 30 && $max_days == 31 && $old_day == 29) {
        $update_day = 30;
      }
      elseif ($month == 2 && $old_day == 30) {
        $update_day = 27 + $leap;
      }
      elseif ($month == 2 && in_array($old_day, [29, 28])) {
        $update_day = 26 + $leap;
      }

      if (!empty($update_day)) {
        $temp->setDate((int) $year, (int) $month, (int) $update_day);
      }
    }

    return $temp;
  }


  /**
   * Remove Months
   *
   * Correctly handle modification of the date object when we are dealing with the end of a month
   *
   * @param \DateTime $date
   * @param int $removeMonths
   * @return \DateTime
   */
  private function _removeMonths(\DateTime $date, int $removeMonths):\DateTime {

    if ($removeMonths == 0) {
      return $date;
    }

    $temp = clone $date;

    $modifier = "-{$removeMonths} months";

    $temp->modify($modifier);

    list($old_month, $old_day, $old_max_days) = explode('-', $date->format('n-j-t'));

    if ((int) $temp->format('m') % 12 != ((12 + (int) $old_month - ($removeMonths % 12)) % 12)) {
      $temp->modify("-{$temp->format('d')} days");
    }
    else {

      if ($old_day == $date->format('t') && $old_day < $temp->format('t')) {
        $temp->modify('+'.($temp->format('t') - $temp->format('d')).' days');
      }
    }

    list($year, $month, $day, $max_days, $leap) = explode('-', $temp->format('Y-n-j-t-L'));

    if ($old_month != $month && $day >= 27) {
      $update_day = null;

      if ($old_month == 2 && $old_day == 29) {
        $update_day = $max_days;
      }
      elseif ($old_month == 2 && $old_day == 28) {
        $update_day = $max_days - $leap;
      }
      elseif ($old_month == 2 && $old_day == 27) {
        $update_day = $max_days - (1 + $leap);
      }
      elseif ($month == 2 && $old_day == 30) {
        $update_day = 27 + $leap;
      }
      elseif ($month == 2 && in_array($old_day, [29, 28])) {
        $update_day = 26 + $leap;
      }
      elseif ($old_max_days == 31 && $max_days == 30 && $old_day == 30) {
        $update_day = 29;
      }
      elseif ($old_max_days == 30 && $max_days == 31 && $old_day == 29) {
        $update_day = 30;
      }

      if (!empty($update_day)) {
        $temp->setDate((int) $year, (int) $month, (int) $update_day);
      }
    }

    return $temp;
  }


  /**
   * No Weekend System
   *
   * If the day is a Saturday or Sunday we change to Monday
   *
   * @param \DateTime $date
   * @return \DateTime
   */
  private function _noWeekendSystem(\DateTime $date):\DateTime {

    while (in_array($date->format('N'), array(6, 7))) {
      $date->modify('+1 day');
    }

    return $date;
  }


  /**
   * Last Business Day Of Month System
   *
   * If the payment should be paid out on the last business day of the month
   *
   * @param \DateTime $date
   * @return \DateTime
   */
  private function _lastBusinessDayOfMonthSystem(\DateTime $date):\DateTime {
    list($day, $days_in_month) = explode('-', $date->format('j-t'));

    $date->modify('+'.($days_in_month - $day).' day');

    //Make certain it is not a Saturday or Sunday, if change to Friday
    while (in_array($date->format('N'), array(6, 7))) {
      $date->modify('-1 day');
    }

    return $date;
  }


  /**
   * Day Of Month System
   *
   * If the payment should be paid out on a specific day of the month
   *
   * @param \DateTime $date
   * @param int $payDay
   * @return \DateTime
   */
  private function _dayOfMonthSystem(\DateTime $date, int $payDay):\DateTime {
    //Ensure we correct the payout day, if the month has less days
    $last_day = $date->format('t');

    if ($payDay > $last_day) {
      $payDay = $last_day;
    }

    //Make certain we end up on the correct day for the payout
    $day = $date->format('j');

    if ($day > $payDay) { //Larger than the set day
      $date->modify('-'.($day - $payDay).' day');
    }
    elseif ($day < $payDay) { //Smaller than set day
      $date->modify('+'.($payDay - $day).' day');
    }

    return $date;
  }


  /**
   * Extended Holding Period
   *
   * If the payment should be paid out at a specific interval, due to the merchant release payments on a set week day.
   *
   * Example when the merchant cutoff is on Thursday, and holdback should be calculated from then:
   * $extraDays = array('monday' => 3,
   *                    'tuesday' => 2,
   *                    'wednesday' => 1,
   *                    'thursday' => 0,
   *                    'friday' => 6,
   *                    'saturday' => 5,
   *                    'sunday' => 4,);
   *
   * @param \DateTime $date
   * @param array $extraDays
   * @return \DateTime
   */
  private function _extendedHoldingPeriod(\DateTime $date, array $extraDays):\DateTime {
    $holding_period = $extraDays[strtolower($date->format('l'))]; //How many days before it is released

    if (empty($holding_period) || $holding_period < 1) {
      return $date;
    }

    $date->modify("+{$holding_period} day");

    return $date;
  }


  /**
   * Periodic Interval
   *
   * Makes it possible to setup queue cutoff times, like every hour, every day at 2pm etc.
   * When the [before] settings are used, we check if the threshold is reached, date object is modified to that time,
   * if it has been reached, it is set to that for the next period according to the [every] setting.
   *
   * - If the [before] hour setting is used in combination with [every] hour, it is ignored.
   * - If only the [before] min setting is used in combination with [every] day, it is ignored.
   * - If the [before] settings is used in combination with [every] week, month or year, it is ignored.
   *
   * Example:
   * $periodic_interval = array('before' => array('hour' => [hour:int], #before is Optional
   *                                              'min' => [min:int]),
   *                            'every' => [type:hour,day,week,month,year]));
   *
   * @param \DateTime $date
   * @param array $settings
   * @return \DateTime
   */
  private function _periodicInterval(\DateTime $date, array $settings):\DateTime {
    $min = (int) $date->format('i');
    $hour = (int) $date->format('G');
    $before_min = $settings['before']['min']??$min;
    $before_hour = $settings['before']['hour']??$hour;

    switch ($settings['every']) {
      case 'hour':
        //If [before] min is used, only increase the hour if we have passed the min restriction
        if ($min >= $before_min) {

          if ($hour == 23) {
            $hour = 0;
            $date->modify('+ 1 day');
          } else {
            $hour += 1;
          }
        }

        $date->setTime($hour, $settings['before']['min']??0, 0);
        break 1;
      case 'day':
        //If [before] hour is used, only increase the hour if we have passed the hour (?and min) restriction
        if ($hour > $before_hour || ($hour == $before_hour && $min >= $before_min)) {
          $date->modify('+1 day');
        }

        if (isset($settings['before']['hour'])) {
          $date->setTime($settings['before']['hour']??$hour, $settings['before']['min']??0, 0);
        }
        break 1;
      case 'week':
        $date->modify('+1 week');
        break 1;
      case 'month':
        $date = $this->_addMonths($date, 1);
        break 1;
      case 'year':
        $date = $this->_modifyDate($date, ['year' => 1]);
        break 1;
      default:
        throw new \TypeError('Invalid passed periodic interval setting!');
    }

    return $date;
  }


  /**
   * Time of Cutoff
   *
   * It today is after the set [hour], it will add a day.
   *
   * Note: the hour follow 24 hour format
   *
   * Example:
   * Cutoff is at 2pm, if the request is processed at 2pm, 2.01pm 3pm etc. we add a day,
   * since the merchant will not process the transaction before tomorrow.
   *
   * @param \DateTime $date
   * @param int $hour
   * @return \DateTime
   */
  private function _timeOfCutoff(\DateTime $date, int $hour):\DateTime {

    if ($date->format('G') >= $hour) {
      $date->modify('+1 day');
    }

    return $date;
  }


  /**
   * Time Of Day
   *
   * If the cutoff time is not a midnight, we can make certain we obey that as well
   *
   * Note: [hour] follow 24 hour format, [min] is 0 to 59
   *
   * Example:
   * $time_of_day => array('hour' => [hour:int],
   *                       'min' => [min:int]);
   *
   * @param \DateTime $date
   * @param array $time
   * @return \DateTime
   */
  private function _timeOfDay(\DateTime $date, array $time):\DateTime {
    $date->setTime($time['hour'], $time['min'], 0);

    return $date;
  }
}