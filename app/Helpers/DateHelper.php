<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format tanggal ke bahasa Indonesia
     * 
     * @param Carbon|string $date
     * @param string $format
     * @return string
     */
    public static function formatIndonesia($date, $format = 'd F Y, H:i')
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        // Set locale ke Indonesia
        $date->locale('id');
        
        return $date->translatedFormat($format) . ' WIB';
    }

    /**
     * Format tanggal untuk tampilan singkat
     * 
     * @param Carbon|string $date
     * @return string
     */
    public static function formatShort($date)
    {
        return self::formatIndonesia($date, 'd M Y, H:i');
    }

    /**
     * Format tanggal untuk tampilan lengkap
     * 
     * @param Carbon|string $date
     * @return string
     */
    public static function formatLong($date)
    {
        return self::formatIndonesia($date, 'l, d F Y \p\u\k\u\l H:i');
    }
}