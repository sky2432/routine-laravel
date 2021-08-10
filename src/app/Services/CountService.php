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
        $rankIds = RankService::getRankIds();

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
                if ($routine->$rank_column === $rankIds['SS']) {
                    $ss++;
                }
                if ($routine->$rank_column === $rankIds['S']) {
                    $s++;
                }
                if ($routine->$rank_column === $rankIds['A']) {
                    $a++;
                }
                if ($routine->$rank_column === $rankIds['B']) {
                    $b++;
                }
                if ($routine->$rank_column === $rankIds['C']) {
                    $c++;
                }
                if ($routine->$rank_column === $rankIds['D']) {
                    $d++;
                }
                if ($routine->$rank_column === $rankIds['E']) {
                    $e++;
                }
                if ($routine->$rank_column === $rankIds['F']) {
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
        $done_days =  self::getDoneDays($routine_id);
        $data['all_days'] = self::countAllDays($done_days);
        [$data['continuous_days'], $data['highest_continuous_days']] = self::countContinuousDays($done_days);
        $data['recovery_count'] = self::countRecovery($done_days);

        return $data;
    }

    public static function countAllDays($done_days)
    {
        $count = 0;
        foreach ($done_days as $key => $value) {
            if ($value !== 0) {
                $count++;
            }
        }

        return $count;
    }

    public static function countContinuousDays($done_days)
    {
        $count = 0;
        $highestCount = 0;
        $today = Carbon::today();
        foreach ($done_days as $key => $value) {
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

    public static function countRecovery($done_days)
    {
        $first = false;
        $status = false;
        $count = 0;
        $recovery = 0;
        foreach ($done_days as $key => $value) {
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

        // デバック用↓
        // echo $routine_id;
        // dump($data);

        return $data;
    }
}
