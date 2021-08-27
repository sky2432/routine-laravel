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
    public static function countRank($user_id)
    {
        $routines = Routine::where('user_id', $user_id)->get();
        $rank_columns = ['total_rank_id', 'highest_continuous_rank_id', 'recovery_rank_id'];
        $rank_ids = RankService::getRankIds();

        $ss = 0;
        $s = 0;
        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $e = 0;
        $f = 0;

        foreach ($routines as $routine) {
            foreach ($rank_columns as $rank_column) {
                if ($routine->$rank_column === $rank_ids['SS']) {
                    $ss++;
                }
                if ($routine->$rank_column === $rank_ids['S']) {
                    $s++;
                }
                if ($routine->$rank_column === $rank_ids['A']) {
                    $a++;
                }
                if ($routine->$rank_column === $rank_ids['B']) {
                    $b++;
                }
                if ($routine->$rank_column === $rank_ids['C']) {
                    $c++;
                }
                if ($routine->$rank_column === $rank_ids['D']) {
                    $d++;
                }
                if ($routine->$rank_column === $rank_ids['E']) {
                    $e++;
                }
                if ($routine->$rank_column === $rank_ids['F']) {
                    $f++;
                }
            }
        }

        $data = [
            ['name' => 'SS', 'count' => $ss],
            ['name' => 'S', 'count' => $s],
            ['name' => 'A', 'count' => $a],
            ['name' => 'B', 'count' => $b],
            ['name' => 'C', 'count' => $c],
            ['name' => 'D', 'count' => $d],
            ['name' => 'E', 'count' => $e],
            ['name' => 'F', 'count' => $f],
        ];

        return $data;
    }

    public static function updateRoutineCountData($routine_id)
    {
        $data = self::getAllCountData($routine_id);
        $item = Routine::find($routine_id);
        $item->total_days = $data['all_days'];
        $item->continuous_days = $data['continuous_days'];
        $item->highest_continuous_days = $data['highest_continuous_days'];
        $item->recovery_count = $data['recovery_count'];
        $item->save();
    }

    public static function getAllCountData($routine_id)
    {
        $done_dates =  self::getDoneDates($routine_id);
        $data['all_days'] = self::countAllDays($done_dates);
        [$data['continuous_days'], $data['highest_continuous_days']] = self::countContinuousDays($done_dates);
        $data['recovery_count'] = self::countRecovery($done_dates);

        return $data;
    }

    public static function countAllDays($done_dates)
    {
        $count = 0;
        foreach ($done_dates as $key => $value) {
            if ($value !== 0) {
                $count++;
            }
        }

        return $count;
    }

    public static function countContinuousDays($done_dates)
    {
        $count = 0;
        $highestCount = 0;
        $today = Carbon::today();
        foreach ($done_dates as $key => $value) {
            $dbDate = new Carbon($key);
            // 今日の場合
            if ($dbDate->eq($today)) {
                if ($value !== 0) {
                    $count++;
                    if ($count > $highestCount) {
                        $highestCount = $count;
                    }
                }
                // 達成していなくても$countを0にしない
                if ($value === 0) {
                    if ($count > $highestCount) {
                        $highestCount = $count;
                    }
                }
            } elseif ($value !== 0) {
                $count++;
            } elseif ($value === 0) {
                if ($count > $highestCount) {
                    $highestCount = $count;
                }
                $count = 0;
            }
        }

        return [$count, $highestCount];
    }

    public static function countRecovery($done_dates)
    {
        $first = false;
        $status = false;
        $count = 0;
        $recovery = 0;

        // 連続日数が
        foreach ($done_dates as $key => $value) {
            if ($first === false && $value === 1) {
                $first = true;
            }
            if ($first === true && $value === 0) {
                $status = true;
                $count = 0;
            }
            if ($status === true && $value === 1) {
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

    public static function getDoneDates($routine_id)
    {
        [$begin, $end] = self::createBeginAndEnd($routine_id);
        $base_period = self::createBasePeriod($begin, $end);
        $done_dates_array = self::calculateDoneDates($routine_id, $begin, $end);

        $done_dates_with_zero_fill = array_replace($base_period, $done_dates_array);

        return $done_dates_with_zero_fill;
    }

    public static function createBeginAndEnd($routine_id)
    {
        $startDate = Routine::where('id', $routine_id)->value('created_at');
        $begin = Carbon::create($startDate->year, $startDate->month, $startDate->day);

        $today = Carbon::today();
        $end = $today->copy()->endOfDay();

        return [$begin, $end];
    }


    public static function createBasePeriod($begin, $end)
    {
        $period = new DatePeriod($begin, new DateInterval('P1D'), $end);//$begin以上$end未満

        foreach ($period as $date) {
            $base_period[$date->format("Y-m-d")] = 0;
        }

        return $base_period;
    }

    public static function calculateDoneDates($routine_id, $first, $last)
    {
        $done_dates_object_array = Record::where('routine_id', $routine_id)
        ->whereBetween('created_at', [$first, $last])//$first以上$last以下
        ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'), DB::raw('count(created_at) as count'))
        ->groupBy('date')
        ->get();

        foreach ($done_dates_object_array as $done_date_object) {
            $done_dates_array[$done_date_object->date] = $done_date_object->count;
        }

        return $done_dates_array;
    }
}
