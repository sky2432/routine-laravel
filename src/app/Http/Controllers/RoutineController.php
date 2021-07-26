<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Services\CountService;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    public function store(Request $request)
    {
        $item = new Routine;
        $item->fill($request->all())->save();

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function show($user_id)
    {
        $items = Routine::where('user_id', $user_id)->get();

        return response()->json([
            'data' => $items
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

    public function countDays($routine_id)
    {
        $data['all_days'] = CountService::countAllDays($routine_id);
        [$data['continuous_days'], $data['highest_continuous_days']] = CountService::countContinuousDays($routine_id);
        $data['recovery_count'] = CountService::countRecovery($routine_id);

        return response()->json([
            'data' => $data
        ], 200);
    }
}
