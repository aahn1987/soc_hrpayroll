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
if (!function_exists('clear_string')) {
    function clear_string($string)
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $string));
    }
}
if (!function_exists('getFileType')) {
    function getFileType($ext)
    {
        return match (strtolower($ext)) {
            'gif', 'png', 'jpg', 'jpeg' => 'Image',
            'doc', 'docx' => 'Document',
            'xls', 'xlsx' => 'Spreadsheet',
            'ppt', 'pptx', 'ppsx' => 'Presentation',
            'pdf' => 'PDF',
            'txt' => 'Text File',
            'zip', 'rar' => 'Compressed File',
            'mp4' => 'Video',
            'mp3' => 'Audio',
            default => 'Unknown',
        };
    }
}
if (!function_exists('getFileIcon')) {
    function getFileIcon($ext)
    {
        return match (strtolower($ext)) {
            'gif', 'png', 'jpg', 'jpeg' => 'image',
            'doc', 'docx' => 'document',
            'xls', 'xlsx' => 'spreadsheet',
            'ppt', 'pptx', 'ppsx' => 'presentation',
            'pdf' => 'pdf',
            'txt' => 'text',
            'zip', 'rar' => 'zip',
            'mp4' => 'video',
            'mp3' => 'audio',
            default => 'file',
        };
    }
}
if (!function_exists('generateViewerHtml')) {
    function generateViewerHtml($ext, $url)
    {
        $ext = strtolower($ext);
        return match ($ext) {
            'gif', 'png', 'jpg', 'jpeg' => '<img class="img-fluid" src="' . $url . '" alt="File">',
            'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'ppsx' =>
            '<iframe class="w-100 vh-50" src="https://view.officeapps.live.com/op/view.aspx?src=' . urlencode($url) . '" frameborder="0"></iframe>',
            'pdf', 'txt' => '<iframe class="w-100 vh-50" src="' . $url . '" frameborder="0"></iframe>',
            'zip', 'rar' => '<a target="_blank" class="navlink" href="' . $url . '">Download File</a>',
            'mp4' => '<video class="w-100 vh-50" controls><source src="' . $url . '" type="video/mp4"></video>',
            'mp3' => '<audio class="w-100" controls><source src="' . $url . '"></audio>',
            default => '<a href="' . $url . '" target="_blank">Download File</a>',
        };
    }
}
if (!function_exists('excel_float')) {
    function excel_float($value): float
    {
        if (!empty($value) && is_numeric($value)) {
            return (float) $value;
        }
        return 0.0;
    }
}
if (!function_exists('check_excel_flag')) {
    function check_excel_flag($value): int
    {
        if (empty($value) || !is_numeric($value) || (float) $value == 0) {
            return 2;
        }
        return 1;
    }
}
if (!function_exists('excel_date')) {
    function excel_date($value): string
    {
        return !empty($value)
            ? date("Y-m-d", strtotime($value))
            : date("Y-m-d", strtotime("1 January 1990"));
    }
}
if (!function_exists('calculate_age')) {
    function calculate_age(string $dob, $age): float
    {
        if (!empty($age)) {
            return (float) $age;
        }
        $dobTime = strtotime($dob);
        $nowTime = time();
        $diffInSeconds = $nowTime - $dobTime;
        $years = $diffInSeconds / (365.25 * 24 * 60 * 60); // Account for leap years
        return round($years, 2);
    }
}
if (!function_exists('excel_payment')) {
    function excel_payment($value): int
    {
        return (empty($value) || strtolower(trim($value)) === 'cash') ? 2 : 1;
    }
}
