<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use App\Models\RecoveryRank;
use App\Models\Routine;
use App\Services\RecordService;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    public function store(Request $request)
    {
        $item = new Routine;
        $item->fill($request->all());
        $item->total_rank_id = Rank::DefaultId();
        $item->continuous_rank_id = Rank::DefaultId();
        $item->recovery_rank_id = RecoveryRank::DefaultId();
        $item->save();

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function show($user_id)
    {
        $items = Routine::with(['totalRank', 'continuousRank', 'recoveryRank'])->where('user_id', $user_id)->get();

        $routines = RecordService::insertTodayRecord($items);

        return response()->json([
            'data' => $routines
        ], 200);
    }

    public function update(Request $request, $routine_id)
    {
        $item = Routine::find($routine_id);
        $item->name = $request->name;
        $item->save();

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function destroy($routine_id)
    {
        Routine::destroy($routine_id);

        return response()->json([], 204);
    }
}
