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

declare(strict_types = 1);

namespace Kaizen\Date\Value;

/**
 * Immutable Date Modify Settings Value Object
 *
 * Validate and store the information/settings for modifying the date
 *
 * @author     Sven Arild Helleland
 * @company    Kaizen Web-Productions (http://www.kaizen-web.com)
 * @version    1.0
 * @copyright  Copyright(C), Kaizen Web-Productions, 2004-2020, All Rights Reserved.
 * @package    ValueObject
 * @subpackage Settings
 */
final class Settings {

  /**
   * Immutable Check
   *
   * @var bool
   */
  private $_mutable = true;

  /**
   * @var array
   */
  private $_settings;

  /**
   * @var \DateTimeZone|null
   */
  private $_timezone = null;


  /**
   * Constructor
   *
   * Information:
   *    Cutoff Hour:
   *      It today is after the set [hour], it will add a day.
   *      If both [day] and [hour] is set, then it only enforce cutoff if the day is identical
   *
   *      Note: [hour] follow 24 hour format, [day] is 1 (Monday) to 7 (Sunday) following ISO-8601, the day is optional
   *
   *    Day of Month:
   *      It will modify the date object to this day.
   *      If a larger date has been chosen, the system will automatically chose the last day of the month instead.
   *
   *      Note: [day] follow the date of the month, 1 to 31.
   *
   *    Time of Day:
   *      It will modify the date object to this time.
   *
   *      Note: [hour] follow 24 hour format, [min] is 0 to 59
   *
   *    No Weekend Start:
   *      Should not start the date modification on a weekend, it move the date to Monday if it is Saturday or Sunday.
   *
   *      Note: Important to note this is checked/done BEFORE any manipulation is done to the date object.
   *
   *    Not End On Weekend:
   *      Should not end the date modification on a weekend, it move the date to Monday if it is Saturday or Sunday.
   *
   *      Note: Important to note this is checked/done AFTER any manipulation is done to the date object.
   *
   *    Last Business Day:
   *      Should end on the last bank business day of the month, this means it should not end on a Saturday or Sunday.
   *      No matter the date of the month, it will move it to the last bank business day of the month.
   *
   *      Note: Important to note this is checked/done AFTER any manipulation is done to the date object.
   *
   *    Period:
   *      The modification you want to add to the date, used to move it a set period into the future.
   *      Supports multiple modifications at once, example updating both seconds, hours and days etc.
   *
   *      Note. [type] should be sec, min, hour, day, week, month or year, [period] should be a positive integer.
   *
   *    Extended Holding:
   *      If the merchant use a week batch, this will ensure that any release calculations will start on the correct day.
   *      It has to be an array that contains [key:monday,tuesday,wednesday,thursday,friday,saturday,sunday] and the value
   *      has to be 0 to 6, meaning how many days to add to reach the week batch day. Each key, and each value can only be
   *      used once.
   *
   *      Note: Important to note this is checked/done BEFORE any manipulation is done to the date object.
   *
   *    Periodic Interval
   *      Makes it possible to setup queue cutoff times, like every hour, every day at 2pm etc.
   *      When the [before] settings are used, we check if the threshold is reached, date object is modified to that time,
   *      if it has been reached, it is set to that for the next period according to the [every] setting.
   *      - If the [before] hour setting is used in combination with [every] hour, it is ignored.
   *      - If only the [before] min setting is used in combination with [every] day, it is ignored.
   *      - If the [before] settings is used in combination with [every] week, month or year, it is ignored.
   *
   *      Note. [every] should be hour, day, week, month or year, [before] is optional, and can contain an array consisting of
   *      [hour] following 24 hour format, [min] is 0 to 59 (only [hour] or [min] is required, not both)
   *
   *
   * Priority Order:
   *    This is the order the modifications will be processed:
   *
   *    1. Switch Timezone (to merchant timezone)
   *    2. Cutoff Hour
   *    3. No Weekend Start
   *    4. Extended Holding
   *    5. Period
   *    6. Periodic Interval
   *    7. a) Day of Month
   *       b) Last Business Day
   *       Note. Only one of these will be executed!
   *    8. Not End On Weekend
   *    9. Time of Day
   *    10. Switch Timezone (back to system timezone)
   *
   *
   * Example:
   * $settings = array('cutoff_hour' => array('hour' => [hour:int],
   *                                          'day' => [day:int]), #day is Optional and follow ISO-8601 for day of week
   *                   'day_of_month' => [day:int],
   *                   'time_of_day' => array('hour' => [hour:int],
   *                                          'min' => [min:int]),
   *                   'no_weekend_start' => [bool],
   *                   'not_end_on_weekend' => [bool],
   *                   'last_business_day' => [bool],
   *                   'period' => array([type] => [period:int],
   *                                     [type] => [period:int],
   *                                     ...), #as many types as you need can be added
   *                   'extended_holding' => array('monday' => 3,
   *                                               'tuesday' => 2,
   *                                               'wednesday' => 1,
   *                                               'thursday' => 0,
   *                                               'friday' => 6,
   *                                               'saturday' => 5,
   *                                               'sunday' => 4),
   *                   'periodic_interval' => array('before' => array('hour' => [hour:int], #before is Optional
   *                                                                  'min' => [min:int]),
   *                                                'every' => [type:hour,day,week,month,year]));
   *
   * @param string $settings  A JSON string containing the settings
   * @param string|null $timezone
   */
  public function __construct(string $settings, ?string $timezone) {

    if ($this->_mutable === false) {
      throw new \BadMethodCallException('Attempt to use immutable value object as mutable.');
    }

    $settings = json_decode($settings, true, 512, JSON_UNESCAPED_UNICODE);

    $this->_validateSettings($settings);

    $this->_settings = $settings;

    if (!empty($timezone)) {
      try {
        $this->_timezone = new \DateTimeZone($timezone);
      } catch (\Exception $e) {
        throw new \TypeError($e->getMessage());
      }
    }

    $this->_mutable = false;
  }

  public function enforceSwitchTimezone(\DateTimeZone $timezone):bool {
    $setting_timezone = new \DateTime('2018-05-08 12:00:00', $this->_timezone);
    $system_timezone = new \DateTime('2018-05-08 12:00:00', $timezone);

    return (!empty($this->_timezone) && $setting_timezone != $system_timezone);
  }

  public function getTimezone():?\DateTimeZone {
    return $this->_timezone;
  }

  public function enforceCutoffHour(\DateTime $date):bool {

    if (!empty($this->_settings['cutoff_hour']['day'])) {
      return ($this->_settings['cutoff_hour']['day'] == $date->format('N'));
    }

    return !empty($this->_settings['cutoff_hour']);
  }

  public function getCutoffHour():int {
    return $this->_settings['cutoff_hour']['hour'];
  }

  public function enforceNoWeekendStart():bool {
    return !empty($this->_settings['no_weekend_start']);
  }

  public function enforceDayOfMonth():bool {
    return !empty($this->_settings['day_of_month']);
  }

  public function getDayOfMonth():int {
    return $this->_settings['day_of_month'];
  }

  public function enforceLastBusinessDayOfMonth():bool {
    return !empty($this->_settings['last_business_day']);
  }

  public function enforceNotEndOnWeekend():bool {
    return !empty($this->_settings['not_end_on_weekend']);
  }

  public function enforceExtendedHoldingPeriod():bool {
    return !empty($this->_settings['extended_holding']);
  }

  public function getExtendedHoldingPeriod():array {
    return $this->_settings['extended_holding'];
  }

  public function enforceTimeOfDay():bool {
    return !empty($this->_settings['time_of_day']);
  }

  public function getTimeOfDay():array {
    return $this->_settings['time_of_day'];
  }

  public function addPeriod():bool {
    return !empty($this->_settings['period']);
  }

  public function getPeriod():array {
    return $this->_settings['period'];
  }

  public function enforcePeriodicInterval():bool {
    return !empty($this->_settings['periodic_interval']);
  }

  public function getPeriodicInterval():array {
    return $this->_settings['periodic_interval'];
  }

  private function _validateSettings(array $settings):void {

    foreach ($settings as $key => $value) {

      switch ($key) {
        case 'cutoff_hour':
          if (!isset($value['hour']) || !is_int($value['hour']) || !in_array($value['hour'], range(0, 23))) {
            throw new \TypeError('The passed "Cutoff Hour" setting is invalid!');
          }

          if (!empty($value['day']) && (!is_int($value['day']) || !in_array($value['day'], range(1, 7)))) {
            throw new \TypeError('The passed "Cutoff Hour" setting is invalid!');
          }

          break 1;
        case 'day_of_month':
          if (empty($value) || !is_int($value) || !in_array($value, range(1, 31))) {
            throw new \TypeError('The passed "Day of Month" setting is invalid!');
          }

          break 1;
        case 'time_of_day':
          if (!isset($value['hour']) || !is_int($value['hour']) || !in_array($value['hour'], range(0, 23)) || !isset($value['min']) || !is_int($value['min']) || !in_array($value['min'], range(0, 59))) {
            throw new \TypeError('The passed "Time of Day" setting is invalid!');
          }

          break 1;
        case 'period':
          $allowed_types = ['sec', 'secs', 'second', 'seconds', 'min', 'mins', 'minute', 'minutes', 'hour', 'hours', 'day', 'days', 'week', 'weeks', 'month', 'months', 'year', 'years', 'modify'];

          foreach ($value as $type => $period) {

            if (!in_array($type, $allowed_types) || ($type != 'modify' && (!is_int($period) || $period < 1)) || ($type == 'modify' && !is_string($period))) {
              throw new \TypeError('The passed "Period" setting is invalid!');
            }
          }

          break 1;
        case 'extended_holding':
          $week_days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
          $week_day_numbers = range(0, 6);

          $week_days_located = [];
          $week_day_numbers_located = [];

          foreach ($value as $name => $day) {

            if (!in_array($name, $week_days) || !is_int($day) || !in_array($day, $week_day_numbers)) {
              throw new \TypeError('The passed "Extended Holding" setting is invalid!');
            }

            if (empty($week_days_located[$name]) && in_array($name, $week_days)) {
              $week_days_located[$name] = true;
            }

            if (empty($week_day_numbers_located[$day]) && in_array($day, $week_day_numbers)) {
              $week_day_numbers_located[$day] = true;
            }
          }

          if (count($value) != 7 || count($week_days_located) != 7 || count($week_day_numbers_located) != 7) {
            throw new \TypeError('The passed "Extended Holding" setting is invalid!');
          }

          break 1;
        case 'periodic_interval':
          $allowed_types_hour = ['hour', 'hours'];
          $allowed_types_minute = ['min', 'minute', 'minutes'];

          if (!empty($value['before'])) {

            foreach ($value['before'] as $type => $period) {

              if (in_array($type, $allowed_types_hour) && (!is_int($period) || !in_array($period, range(0,23)))) {
                throw new \TypeError('The passed "Periodic Interval" setting is invalid!');
              }
              elseif (in_array($type, $allowed_types_minute) && (!is_int($period) || !in_array($period, range(0,59)))) {
                throw new \TypeError('The passed "Periodic Interval" setting is invalid!');
              }
              elseif (!in_array($type, $allowed_types_hour) && !in_array($type, $allowed_types_minute)) {
                throw new \TypeError('The passed "Periodic Interval" setting is invalid!');
              }
            }
          }

          if (empty($value['every']) || !in_array($value['every'], ['hour', 'day', 'week', 'month', 'year'])) {
            throw new \TypeError('The passed "Periodic Interval" setting is invalid!');
          }

          break 1;
        case 'no_weekend_start':
          if (!is_bool($value)) {
            throw new \TypeError('The passed "No Weekend Start" setting is invalid!');
          }

          break 1;
        case 'not_end_on_weekend':
          if (!is_bool($value)) {
            throw new \TypeError('The passed "Not End On Weekend" setting is invalid!');
          }

          break 1;
        case 'last_business_day':
          if (!is_bool($value)) {
            throw new \TypeError('The passed "Last Business Day" setting is invalid!');
          }

          break 1;
        default:
          throw new \TypeError('Invalid setting type "'.$key.'" located!');
      }
    }
  }
}