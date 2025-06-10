<?php

if (!function_exists('random_password')) {
    function random_password($length = 8)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }
}
if (!function_exists(('number_of_working_days'))) {
    function number_of_working_days($from, $to)
    {
        $workingDays = [1, 2, 3, 4, 7];
        $from = new DateTime($from);
        $to = new DateTime($to);
        $to->modify('+1 day');
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), $workingDays)) {
                continue;
            }

            $days++;
        }
        return $days;
    }
}

