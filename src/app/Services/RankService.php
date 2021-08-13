<?php

namespace App\Services;

use App\Models\Rank;
use App\Models\Routine;

class RankService
{
    public static function checkAllRank($routine_id)
    {
        $rank_up_data = [];

        $total_res = self::checkTotalDaysRank($routine_id);
        if ($total_res) {
            $rank_up_data[] = $total_res;
        }
        $continuous_res = self::checkContinuousDaysRank($routine_id);
        if ($continuous_res) {
            $rank_up_data[] = $continuous_res;
        }
        $recovery_res = self::checkRecoveryRank($routine_id);
        if ($recovery_res) {
            $rank_up_data[] = $recovery_res;
        }

        return $rank_up_data;
    }

    public static function checkTotalDaysRank($routine_id)
    {
        $rank_ids = self::getRankIds();
        $item = Routine::find($routine_id);
        $data = [
            ['days' => 180, 'rank' => config('const.RANK')[7]],
            ['days' => 120, 'rank' => config('const.RANK')[6]],
            ['days' => 90, 'rank' => config('const.RANK')[5]],
            ['days' => 60, 'rank' => config('const.RANK')[4]],
            ['days' => 30, 'rank' => config('const.RANK')[3]],
            ['days' => 14, 'rank' => config('const.RANK')[2]],
            ['days' => 7, 'rank' => config('const.RANK')[1]],
            ['days' => 0, 'rank' => config('const.RANK')[0]],
        ];

        foreach ($data as $value) {
            if ($item->total_days >= $value['days']) {
                if ($item->total_rank_id === $rank_ids[$value['rank']]) {
                    return;
                }
                $item->update(['total_rank_id' => $rank_ids[$value['rank']]]);

                $rank_up_data['name'] = '累計日数';
                $rank_up_data['rank_name'] = Rank::Name($rank_ids[$value['rank']]);

                return $rank_up_data;
            }
        }
    }

    public static function checkContinuousDaysRank($routine_id)
    {
        $rank_ids = self::getRankIds();
        $item = Routine::find($routine_id);

        $data = [
            ['days' => 90, 'rank' => config('const.RANK')[7]],
            ['days' => 60, 'rank' => config('const.RANK')[6]],
            ['days' => 30, 'rank' => config('const.RANK')[5]],
            ['days' => 21, 'rank' => config('const.RANK')[4]],
            ['days' => 14, 'rank' => config('const.RANK')[3]],
            ['days' => 7, 'rank' => config('const.RANK')[2]],
            ['days' => 3, 'rank' => config('const.RANK')[1]],
            ['days' => 0, 'rank' => config('const.RANK')[0]],
        ];

        foreach ($data as $value) {
            if ($item->highest_continuous_days >= $value['days']) {
                if ($item->highest_continuous_rank_id === $rank_ids[$value['rank']]) {
                    return;
                }
                $item->update(['highest_continuous_rank_id' => $rank_ids[$value['rank']]]);

                $rank_up_data['name'] = '最高継続日数';
                $rank_up_data['rank_name'] = Rank::Name($rank_ids[$value['rank']]);

                return $rank_up_data;
            }
        }
    }

    public static function checkRecoveryRank($routine_id)
    {
        $rank_ids = self::getRankIds();
        $item = Routine::find($routine_id);

        $data = [
            ['count' => 18, 'rank' => config('const.RANK')[7]],
            ['count' => 15, 'rank' => config('const.RANK')[6]],
            ['count' => 12, 'rank' => config('const.RANK')[5]],
            ['count' => 9, 'rank' => config('const.RANK')[4]],
            ['count' => 6, 'rank' => config('const.RANK')[3]],
            ['count' => 3, 'rank' => config('const.RANK')[2]],
            ['count' => 1, 'rank' => config('const.RANK')[1]],
            ['count' => 0, 'rank' => config('const.RANK')[0]],
        ];


        foreach ($data as $value) {
            if ($item->recovery_count >= $value['count']) {
                if ($item->recovery_rank_id === $rank_ids[$value['rank']]) {
                    return;
                }
                $item->update(['recovery_rank_id' => $rank_ids[$value['rank']]]);

                $rank_up_data['name'] = 'リカバリー';
                $rank_up_data['rank_name'] = Rank::Name($rank_ids[$value['rank']]);

                return $rank_up_data;
            }
        }
    }

    public static function getRankIds()
    {
        $ranks = Rank::all();

        $rank_names = config('const.RANK');

        foreach ($ranks as $rank) {
            foreach ($rank_names as $rank_name) {
                if ($rank->name === $rank_name) {
                    $rank_ids[$rank_name] = $rank->id;
                    break;
                }
            }
        }

        return $rank_ids;
    }
}
