<?php
/**
 * Class HijriDate
 *
 * This class provides an example of how to add author details.
 *
 * @author    Hussain Jawadwala
 * @version   1.0
 * @since     2024-05-18
 * @link      https://www.zolute.consulting More info here
 */
class HijriDate {
    public static function gregorianToHijri($date) {
        $day = (int)$date->format('d');
        $month = (int)$date->format('m');
        $year = (int)$date->format('Y');

        $m = $month;
        $y = $year;
        if ($m < 3) {
            $y -= 1;
            $m += 12;
        }

        $a = floor($y / 100.);
        $b = 2 - $a + floor($a / 4.);

        if ($year < 1583) $b = 0;
        if ($year == 1582) {
            if ($month > 10)  $b = -10;
            if ($month == 10) {
                $b = 0;
                if ($day > 4) $b = -10;
            }
        }

        $jd = floor(365.25 * ($y + 4716)) + floor(30.6001 * ($m + 1)) + $day + $b - 1524;
        $b = 0;
        if ($jd > 2299160) {
            $a = floor(($jd - 1867216.25) / 36524.25);
            $b = 1 + $a - floor($a / 4.);
        }
        $bb = $jd + $b + 1524;
        $cc = floor(($bb - 122.1) / 365.25);
        $dd = floor(365.25 * $cc);
        $ee = floor(($bb - $dd) / 30.6001);
        $day = ($bb - $dd) - floor(30.6001 * $ee);
        $month = $ee - 1;
        if ($ee > 13) {
            $cc += 1;
            $month = $ee - 13;
        }
        $year = $cc - 4716;

        $wd = $jd % 7;
        $iyear = 10631. / 30.;
        $epochastro = 1948084;
        $epochcivil = 1948085;

        $shift1 = 8.01 / 60.;

        $z = $jd - $epochastro;
        $cyc = floor($z / 10631.);
        $z = $z - 10631 * $cyc;
        $j = floor(($z - $shift1) / $iyear);
        $iy = 30 * $cyc + $j;
        $z = $z - floor($j * $iyear + $shift1);
        $im = floor(($z + 28.5001) / 29.5);
        if ($im == 13) $im = 12;
        $id = $z - floor(29.5001 * $im - 29);

        return array($id, $im, $iy);
    }

    public static function getHijriDate($gregorianDate,$numMonth=false,$seperator=" ") {
        $date = new DateTime($gregorianDate);
        list($hijriDay, $hijriMonth, $hijriYear) = self::gregorianToHijri($date);

        $months = ["Muharram", "Safar", "Rabi' al-awwal", "Rabi' al-thani", "Jumada al-awwal", "Jumada al-thani",
                   "Rajab", "Sha'ban", "Ramadan", "Shawwal", "Dhu al-Qi'dah", "Dhu al-Hijjah"];
        $month = ($numMonth)? $hijriMonth:$months[$hijriMonth - 1];

        return $hijriDay . $seperator . $month . $seperator . $hijriYear;
    }
}
