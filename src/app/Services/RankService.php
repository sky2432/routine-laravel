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
            ['days' => 180, 'rank' => 'god'],
            ['days' => 120, 'rank' => 'emperor'],
            ['days' => 90, 'rank' => 'king'],
            ['days' => 60, 'rank' => 'saint'],
            ['days' => 30, 'rank' => 'advanced'],
            ['days' => 14, 'rank' => 'intermediate'],
            ['days' => 7, 'rank' => 'beginner'],
            ['days' => 0, 'rank' => 'apprentice'],
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
            ['days' => 90, 'rank' => 'god'],
            ['days' => 60, 'rank' => 'emperor'],
            ['days' => 30, 'rank' => 'king'],
            ['days' => 21, 'rank' => 'saint'],
            ['days' => 14, 'rank' => 'advanced'],
            ['days' => 7, 'rank' => 'intermediate'],
            ['days' => 3, 'rank' => 'beginner'],
            ['days' => 0, 'rank' => 'apprentice'],
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
        $items = Rank::all();

        $data = [
            ['name' => '見習い', 'key' => 'apprentice'],
            ['name' => '初級', 'key' => 'beginner'],
            ['name' => '中級', 'key' => 'intermediate'],
            ['name' => '上級', 'key' => 'advanced'],
            ['name' => '聖級', 'key' => 'saint'],
            ['name' => '王級', 'key' => 'king'],
            ['name' => '帝級', 'key' => 'emperor'],
            ['name' => '神級', 'key' => 'god'],
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
