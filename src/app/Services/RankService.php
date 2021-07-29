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
        $rank_up['total_rank'] = self::checkTotalDaysRank($routine_id, $count_data['all_days']);
        $rank_up['highest_continuous_rank'] = self::checkContinuousDaysRank($routine_id, $count_data['highest_continuous_days']);
        $rank_up['recovery_rank'] = self::checkRecoveryRank($routine_id, $count_data['recovery_count']);

        return $rank_up;
    }

    public static function checkTotalDaysRank($routine_id, $total_days)
    {
        $rankIds = self::getRankIds();
        $item = Routine::find($routine_id);
        $data = [
            ['days' => 365, 'rank' => 'god'],
            ['days' => 270, 'rank' => 'emperor'],
            ['days' => 180, 'rank' => 'king'],
            ['days' => 90, 'rank' => 'saint'],
            ['days' => 30, 'rank' => 'advanced'],
            ['days' => 14, 'rank' => 'intermediate'],
            ['days' => 7, 'rank' => 'beginner'],
            ['days' => 0, 'rank' => 'apprentice'],
        ];

        foreach ($data as $value) {
            if ($total_days >= $value['days']) {
                if ($item->total_rank_id === $rankIds[$value['rank']]) {
                    return false;
                }
                $item->update(['total_rank_id' => $rankIds[$value['rank']]]);
                return true;
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
                    return false;
                }
                $item->update(['highest_continuous_rank_id' => $rankIds[$value['rank']]]);
                return true;
            }
        }
    }

    public static function checkRecoveryRank($routine_id, $recovery_count)
    {
        $rankIds = self::getRecoveryRankIds();
        $item = Routine::find($routine_id);

        $data = [
            ['days' => 15, 'rank' => 'immortal'],
            ['days' => 12, 'rank' => 'rebirth'],
            ['days' => 9, 'rank' => 'resuscitation'],
            ['days' => 6, 'rank' => 'persistence'],
            ['days' => 3, 'rank' => 'revival'],
            ['days' => 0, 'rank' => 'apprentice'],
        ];

        foreach ($data as $value) {
            if ($recovery_count >= $value['days']) {
                if ($item->recovery_rank_id === $rankIds[$value['rank']]) {
                    return false;
                }
                $item->update(['recovery_rank_id' => $rankIds[$value['rank']]]);
                return true;
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
