<?php

namespace App\Traits;

trait DatetimeManipulator
{
    /**
     * Mutate a given value to adjust the date on a given Carbon attribute
     * 
     * @param string $value The time to set
     * @param string $attr  The name of the Carbon attribute
     * @return boolean
     */
    protected function dateOnDateTimeAttributeMutator($value, $attr) {
        $date_regex = '/(?P<year>[0-9]{2,4})[^A-Za-z0-9]*(?P<month>[0-9]{2})[^A-Za-z0-9]*(?P<day>[0-9]{2})[^A-Za-z0-9]*/';
        
        if (preg_match($date_regex, $value, $matches)) {
            if (intval($matches['year']) < 1000) {
                $matches['year'] = intval($matches['year']) + 2000;
            }

            if (!empty($this->attributes[$attr])) {
                $datetime = $this->{$attr};
                $datetime->setYear($matches['year']);
                $datetime->setMonth($matches['month']);
                $datetime->setDay($matches['day']);
            } else {
                $datetime = Carbon::create($matches['year'], $matches['month'], $matches['day']);
            }

            $this->attributes[$attr] = $datetime;

            return true;
        }

        return false;
    }

    /**
     * Mutate a given value to adjust the time on a given Carbon attribute
     * 
     * @param string $value The time to set
     * @param string $attr  The name of the Carbon attribute
     * @return boolean
     */
    protected function timeOnDateTimeAttributeMutator($value, $attr) {
        $time_regex = '/(?P<hour>[0-1][0-9]):(?P<minute>[0-5][0-9])(?P<ampm>[aApP][.]?[mM][.]?)?/';
        
        if (preg_match($time_regex, $value, $matches)) {
            if (!empty($matches['ampm'])) {
                if (str_contains(strtolower($matches['ampm']), 'p') && intval($matches['hour']) < 12) {
                    $matches['hour'] = intval($matches['hour']) + 12;
                }
            }
            if (empty($this->attributes[$attr])) {
                $datetime = Carbon::now();
            } else {
                $datetime = $this->{$attr};
            }

            $datetime->setTime($matches['hour'], $matches['minute'], 0);

            $this->attributes[$attr] = $datetime;

            return true;
        }

        return false;
    }

    /**
     * Return the formatted string from a DateTime object if not null
     *
     * @param string $attr The attribute to access
     * @param string $format The format to return
     * @return string|null
     */
    protected function dateTimeFormatAccessor($attr, $format) {
        if ($this->{$attr} instanceof \DateTime) {
            return $this->{$attr}->format($format);
        }

        return null;
    }
}
