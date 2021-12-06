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
        $routine = new Routine;
        $routine->fill($request->all());
        $default_id = Rank::DefaultId();
        $routine->total_rank_id = $default_id;
        $routine->highest_continuous_rank_id = $default_id;
        $routine->recovery_rank_id = $default_id;
        $routine->save();

        return response()->json([
            'message' => 'Create routine successfuly'
        ], 200);
    }

    public function show($user_id)
    {
        $routines = Routine::WithChildTable()->where('user_id', $user_id)->where('is_archive', false)->get();

        $routines_with_today_record = RecordService::insertTodayRecord($routines);

        return response()->json([
            'data' => $routines_with_today_record
        ], 200);
    }

    public function showArchive($user_id)
    {
        $routines = Routine::WithChildTable()->where('user_id', $user_id)->where('is_archive', true)->get();

        return response()->json([
            'data' => $routines
        ], 200);
    }

    public function update(Request $request, $routine_id)
    {
        $routine = Routine::find($routine_id);
        $routine->update(['name' => $request->name]);

        return response()->json([
            'data' => $routine
        ], 200);
    }

    public function updateArchive(Request $request)
    {
        $routine = Routine::find($request->routine_id);
        $routine->update(['is_archive' => !$routine->is_archive]);

        return response()->json([
            'data' => $routine
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
