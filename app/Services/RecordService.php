<?php

namespace App\Services;

use App\Models\Record;
use Carbon\Carbon;

class RecordService
{
    public static function insertTodayRecord($items)
    {
        $today = new Carbon('today');
        $month = sprintf('%02d', $today->month);
        $day = sprintf('%02d', $today->day);
        $today_string = "{$today->year}-{$month}-{$day}";

        foreach ($items as $item) {
            $today_record = Record::where('routine_id', $item->id)->firstWhere('created_at', 'like', "$today_string%");
            if ($today_record) {
                $item->today_record = $today_record;
            } else {
                $item->today_record = null;
            }
        }

        return $items;
    }
}
