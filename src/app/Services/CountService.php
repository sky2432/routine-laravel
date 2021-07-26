<?php

namespace App\Services;

use App\Models\Record;
use App\Models\Routine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;

class CountService
{
    public static function getDoneDays($routine_id)
    {
        $startDate = Routine::where('id', $routine_id)->value('created_at');
        $begin = Carbon::create($startDate->year, $startDate->month, $startDate->day);

        $today = Carbon::today();
        $end = $today->copy()->endOfDay();

        $period = new DatePeriod($begin, new DateInterval('P1D'), $end);//$begin以上$rangeEnd未満

        $dbData = [];

        foreach ($period as $date) {
            $range[$date->format("Y-m-d")] = 0;
        }

        $data = Record::where('routine_id', $routine_id)
        ->whereBetween('created_at', [$begin, $end])//$begin以上$dbEnd以下
        ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day'), DB::raw('count(created_at) as count'))
        ->groupBy('day')
        ->get();

        foreach ($data as $val) {
            $dbData[$val->day] = $val->count;
        }

        $data = array_replace($range, $dbData);

        return $data;
    }

    public static function countAllDays($routine_id)
    {
        $data = Record::where('routine_id', $routine_id)
        ->count();

        return $data;
    }

    public static function countContinuousDays($routine_id)
    {
        $data =  self::getDoneDays($routine_id);

        $count = 0;
        $highestCount = 0;
        $today = Carbon::today();
        foreach ($data as $key => $value) {
            $dbDate = new Carbon($key);
            if ($dbDate->eq($today)) {
                if ($value !== 0) {
                    $count++;
                    if ($highestCount < $count) {
                        $highestCount = $count;
                    }
                }
                if ($value === 0) {
                    if ($highestCount < $count) {
                        $highestCount = $count;
                    }
                }
            } elseif ($value !== 0) {
                $count++;
            } elseif ($value === 0) {
                if ($highestCount < $count) {
                    $highestCount = $count;
                    $count = 0;
                }
                $count = 0;
            }
        }

        return [$count, $highestCount];
    }

    public static function countRecovery($routine_id)
    {
        $data =  self::getDoneDays($routine_id);

        $first = false;
        $status = false;
        $count = 0;
        $recovery = 0;
        foreach ($data as $key => $value) {
            if ($value === 1 && $first === false) {
                $first = true;
            }
            if ($value === 0  && $first === true) {
                $status = true;
                $count = 0;
            }
            if ($value === 1 && $status === true) {
                $count++;
                if ($count === 2) {
                    $recovery++;
                    $count = 0;
                    $status = false;
                }
            }
        }

        return $recovery;
    }
}
