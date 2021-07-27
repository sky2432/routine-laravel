<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Services\RankService;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function store(Request $request)
    {
        $item = new Record();
        $item->fill($request->all())->save();

        RankService::checkAllRank($request->routine_id);

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function destroy($record_id)
    {
        $item = Record::find($record_id);
        Record::destroy($record_id);

        RankService::checkAllRank($item->routine_id);

        return response()->json([], 204);
    }
}
