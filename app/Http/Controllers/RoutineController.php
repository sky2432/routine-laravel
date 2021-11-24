<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use App\Models\Routine;
use App\Services\CountService;
use App\Services\RecordService;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    public function store(Request $request)
    {
        $item = new Routine;
        $item->fill($request->all());
        $default_id = Rank::DefaultId();
        $item->total_rank_id = $default_id;
        $item->highest_continuous_rank_id = $default_id;
        $item->recovery_rank_id = $default_id;
        $item->save();

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function show($user_id)
    {
        $items = Routine::WithChildTable()->where('user_id', $user_id)->where('is_archive', false)->get();

        $routines = RecordService::insertTodayRecord($items);

        return response()->json([
            'data' => $routines
        ], 200);
    }

    public function showArchive($user_id)
    {
        $items = Routine::WithChildTable()->where('user_id', $user_id)->where('is_archive', true)->get();

        return response()->json([
            'data' => $items
        ], 200);
    }

    public function update(Request $request, $routine_id)
    {
        $item = Routine::find($routine_id);
        $item->update(['name' => $request->name]);

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function updateArchive(Request $request)
    {
        $item = Routine::find($request->routine_id);
        $item->update(['is_archive' => !$item->is_archive]);

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function destroy($routine_id)
    {
        Routine::destroy($routine_id);

        return response()->json([], 204);
    }

    public function getRankCount($user_id)
    {
        $rank_count = CountService::countRank($user_id);

        return response()->json([
            'data' => $rank_count
        ], 200);
    }
}