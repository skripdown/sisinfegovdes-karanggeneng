<?php


namespace App\Http\back;


class _Datetime
{
    public static function setDate($day, $month, $year, $delimiter=''): string {
        if ($month === 1) $month = 'januari';
        else if ($month === 2) $month = 'februari';
        else if ($month === 3) $month = 'maret';
        else if ($month === 4) $month = 'april';
        else if ($month === 5) $month = 'mei';
        else if ($month === 6) $month = 'juni';
        else if ($month === 7) $month = 'juli';
        else if ($month === 8) $month = 'agustus';
        else if ($month === 9) $month = 'september';
        else if ($month === 10) $month = 'oktober';
        else if ($month === 11) $month = 'november';
        else $month = 'desember';

        return $day . $delimiter . $month . $delimiter . $year;
    }

    public static function setAge($year, $month=null, $day=null): int {
        if ($month == null)
            return date('Y') - $year;
        if ($day == null) {
            $age = date('Y') - $year;
            if ($month <= (date('m') + 0)) {
                return $age + 1;
            }
            return $age;
        }
        $age = date('Y') - $year;
        if ($month <= (date('m') + 0) && $day <= (date('d') + 0))
            $age += 1;

        return $age;
    }

    public static function convert_timestamp($timestamp, $delimiter_date=' ', $delimiter_time=':', $date_='', $time_='', $timezone=''):string {
        $pattern = '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})/m';
        preg_match($pattern, $timestamp, $match);
        $date    = self::setDate($match[3], $match[2], $match[1], $delimiter_date);
        $time    = abs((12 - $match[4]) . $delimiter_time . $match[5] . $delimiter_time . $match[6]);
        return $date_ . $date . $time_ . $time . $timezone;
    }
}
