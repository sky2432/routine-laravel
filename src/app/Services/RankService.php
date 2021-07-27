<?php

namespace App\Services;

use App\Models\Rank;
use App\Models\Routine;
use App\Services\CountService;

class RankService
{
    public static function checkAllRank($routine_id)
    {
        $count_data = CountService::getAllCountData($routine_id);
        self::checkTotalDaysRank($routine_id, $count_data['all_days']);
        self::checkContinuousDaysRank($routine_id, $count_data['highest_continuous_days']);
        self::checkRecoveryRank($routine_id, $count_data['recovery_count']);
    }

    public static function checkTotalDaysRank($routine_id, $total_days)
    {
        $rankIds = self::getRankIds();
        $item = Routine::find($routine_id);
        if ($total_days >= 365) {
            $item->update(['total_rank_id' => $rankIds['god']]);
            return;
        }
        if ($total_days >= 270) {
            $item->update(['total_rank_id' => $rankIds['emperor']]);
            return;
        }
        if ($total_days >= 180) {
            $item->update(['total_rank_id' => $rankIds['king']]);
            return;
        }
        if ($total_days >= 90) {
            $item->update(['total_rank_id' => $rankIds['saint']]);
            return;
        }
        if ($total_days >= 30) {
            $item->update(['total_rank_id' => $rankIds['advanced']]);
            return;
        }
        if ($total_days >= 14) {
            $item->update(['total_rank_id' => $rankIds['intermediate']]);
            return;
        }
        if ($total_days >= 7) {
            $item->update(['total_rank_id' => $rankIds['beginner']]);
            return;
        }
        if ($total_days >= 0) {
            $item->update(['total_rank_id' => $rankIds['apprentice']]);
            return;
        }
    }

    public static function checkContinuousDaysRank($routine_id, $continuous_days)
    {
        $rankIds = self::getRankIds();
        $item = Routine::find($routine_id);
        if ($continuous_days >= 90) {
            $item->update(['continuous_rank_id' => $rankIds['god']]);
            return;
        }
        if ($continuous_days >= 60) {
            $item->update(['continuous_rank_id' => $rankIds['emperor']]);
            return;
        }
        if ($continuous_days >= 30) {
            $item->update(['continuous_rank_id' => $rankIds['king']]);
            return;
        }
        if ($continuous_days >= 21) {
            $item->update(['continuous_rank_id' => $rankIds['saint']]);
            return;
        }
        if ($continuous_days >= 14) {
            $item->update(['continuous_rank_id' => $rankIds['advanced']]);
            return;
        }
        if ($continuous_days >= 7) {
            $item->update(['continuous_rank_id' => $rankIds['intermediate']]);
            return;
        }
        if ($continuous_days >= 3) {
            $item->update(['continuous_rank_id' => $rankIds['beginner']]);
            return;
        }
        if ($continuous_days >= 0) {
            $item->update(['continuous_rank_id' => $rankIds['apprentice']]);
            return;
        }
    }

    public static function checkRecoveryRank($routine_id, $recovery_count)
    {
        $rankIds = self::getRecoveryRankIds();
        $item = Routine::find($routine_id);
        if ($recovery_count >= 15) {
            $item->update(['recovery_rank_id' => $rankIds['immortal']]);
            return;
        }
        if ($recovery_count >= 12) {
            $item->update(['recovery_rank_id' => $rankIds['rebirth']]);
            return;
        }
        if ($recovery_count >= 9) {
            $item->update(['recovery_rank_id' => $rankIds['resuscitation']]);
            return;
        }
        if ($recovery_count >= 6) {
            $item->update(['recovery_rank_id' => $rankIds['persistence']]);
            return;
        }
        if ($recovery_count >= 3) {
            $item->update(['recovery_rank_id' => $rankIds['revival']]);
            return;
        }
        if ($recovery_count >= 0) {
            $item->update(['recovery_rank_id' => $rankIds['apprentice']]);
            return;
        }
    }

    public static function getRankIds()
    {
        $items = Rank::all();

        foreach ($items as $item) {
            if ($item->name === '見習い') {
                $rankIds['apprentice'] = $item->id;
            }
            if ($item->name === '初級') {
                $rankIds['beginner'] = $item->id;
            }
            if ($item->name === '中級') {
                $rankIds['intermediate'] = $item->id;
            }
            if ($item->name === '上級') {
                $rankIds['advanced'] = $item->id;
            }
            if ($item->name === '聖級') {
                $rankIds['saint'] = $item->id;
            }
            if ($item->name === '王級') {
                $rankIds['king'] = $item->id;
            }
            if ($item->name === '帝級') {
                $rankIds['emperor'] = $item->id;
            }
            if ($item->name === '神級') {
                $rankIds['god'] = $item->id;
            }
        }

        return $rankIds;
    }

    public static function getRecoveryRankIds()
    {
        $items = Rank::all();

        foreach ($items as $item) {
            if ($item->name === '見習い') {
                $rankIds['apprentice'] = $item->id;
            }
            if ($item->name === '復活') {
                $rankIds['revival'] = $item->id;
            }
            if ($item->name === '不屈') {
                $rankIds['persistence'] = $item->id;
            }
            if ($item->name === '蘇生') {
                $rankIds['resuscitation'] = $item->id;
            }
            if ($item->name === '転生') {
                $rankIds['rebirth'] = $item->id;
            }
            if ($item->name === '不死') {
                $rankIds['immortal'] = $item->id;
            }
        }

        return $rankIds;
    }
}
