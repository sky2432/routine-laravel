<?php

namespace App\Services;

use App\Models\Rank;
use App\Models\RecoveryRank;
use App\Models\Routine;
use App\Services\CountService;

class RankService
{
    public static function checkAllRank($routine_id)
    {
        $count_data = CountService::getAllCountData($routine_id);
        $rank_up = [];

        $total_res = self::checkTotalDaysRank(
            $routine_id,
            $count_data['all_days']
        );
        if ($total_res) {
            $rank_up[] = $total_res;
        }

        $continuous_res = self::checkContinuousDaysRank($routine_id, $count_data['highest_continuous_days']);
        if ($continuous_res) {
            $rank_up[] = $continuous_res;
        }

        $recovery_res = self::checkRecoveryRank($routine_id, $count_data['recovery_count']);
        if ($recovery_res) {
            $rank_up[] = $recovery_res;
        }


        return $rank_up;
    }

    public static function checkTotalDaysRank($routine_id, $total_days)
    {
        $rankIds = self::getRankIds();
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
            if ($total_days >= $value['days']) {
                if ($item->total_rank_id === $rankIds[$value['rank']]) {
                    return;
                }
                $item->update(['total_rank_id' => $rankIds[$value['rank']]]);

                $rank['name'] = '累計日数';
                $rank['rank_name'] = Rank::where('id', $rankIds[$value['rank']])->value('name');

                return $rank;
            }
        }
    }

    public static function checkContinuousDaysRank($routine_id, $continuous_days)
    {
        $rankIds = self::getRankIds();
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
            if ($continuous_days >= $value['days']) {
                if ($item->highest_continuous_rank_id === $rankIds[$value['rank']]) {
                    return;
                }
                $item->update(['highest_continuous_rank_id' => $rankIds[$value['rank']]]);

                $rank['name'] = '最高継続日数';
                $rank['rank_name'] = Rank::where('id', $rankIds[$value['rank']])->value('name');

                return $rank;
            }
        }
    }

    public static function checkRecoveryRank($routine_id, $recovery_count)
    {
        $rankIds = self::getRecoveryRankIds();
        $item = Routine::find($routine_id);

        $data = [
            ['days' => 12, 'rank' => 'immortal'],
            ['days' => 9, 'rank' => 'rebirth'],
            ['days' => 6, 'rank' => 'resuscitation'],
            ['days' => 3, 'rank' => 'persistence'],
            ['days' => 1, 'rank' => 'revival'],
            ['days' => 0, 'rank' => 'apprentice'],
        ];

        foreach ($data as $value) {
            if ($recovery_count >= $value['days']) {
                if ($item->recovery_rank_id === $rankIds[$value['rank']]) {
                    return;
                }
                $item->update(['recovery_rank_id' => $rankIds[$value['rank']]]);

                $rank['name'] = 'リカバリー';
                $rank['rank_name'] = Rank::where('id', $rankIds[$value['rank']])->value('name');

                return $rank;
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
                    $rankIds[$rank_name] = $rank->id;
                    break;
                }
            }
        }

        return $rankIds;
    }

    public static function getRecoveryRankIds()
    {
        $items = RecoveryRank::all();

        $data = [
            ['name' => '見習い', 'key' => 'apprentice'],
            ['name' => '復活', 'key' => 'revival'],
            ['name' => '不屈', 'key' => 'persistence'],
            ['name' => '蘇生', 'key' => 'resuscitation'],
            ['name' => '転生', 'key' => 'rebirth'],
            ['name' => '不死', 'key' => 'immortal'],
        ];

        foreach ($items as $item) {
            foreach ($data as $value) {
                if ($item->name === $value['name']) {
                    $rankIds[$value['key']] = $item->id;
                    break;
                }
            }
        }

        return $rankIds;
    }
}
