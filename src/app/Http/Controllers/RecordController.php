<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Services\CountService;
use App\Services\RankService;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function store(Request $request)
    {
        $item = new Record();
        $item->fill($request->all())->save();

        $rank_up = $this->updateCountAndRank($request->routine_id);

        return response()->json([
            'data' => $item,
            'rank_up' => $rank_up,
        ], 200);
    }

    public function destroy($record_id)
    {
        $item = Record::find($record_id);
        Record::destroy($record_id);

        $this->updateCountAndRank($item->routine_id);

        return response()->json([], 204);
    }

    public function updateCountAndRank($routine_id)
    {
        CountService::updateRoutineCountData($routine_id);
        $rank_up = RankService::checkAllRank($routine_id);

        return $rank_up;
    }
}
