<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Routine;
use App\Services\CountService;
use App\Services\RankService;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function show($routine_id)
    {
        $records = Record::where('routine_id', $routine_id)->get();

        return response()->json([
            'data' => $records
        ], 200);
    }


    public function store(Request $request)
    {
        $record = new Record();
        $record->fill($request->all())->save();

        $rank_up_data = $this->updateCountAndRank($request->routine_id);

        $routine_name = Routine::where('id', $request->routine_id)->value('name');

        return response()->json([
            'routine_name' => $routine_name,
            'rank_up_data' => $rank_up_data,
        ], 200);
    }

    public function destroy($record_id)
    {
        $record = Record::find($record_id);
        Record::destroy($record_id);

        $this->updateCountAndRank($record->routine_id);

        return response()->json([], 204);
    }

    public function updateCountAndRank($routine_id)
    {
        CountService::updateRoutineCountData($routine_id);
        $rank_up_data = RankService::checkAllRank($routine_id);

        return $rank_up_data;
    }
}
