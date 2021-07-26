<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Routine;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\DB;

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
        $data['all_days'] = $this->countAllDays($routine_id);
        [$data['continuous_days'], $data['highest_continuous_days']] = $this->countContinuousDays($routine_id);

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function countAllDays($routine_id)
    {
        $data = Record::where('routine_id', $routine_id)
        ->count();

        return $data;
    }

    public function countContinuousDays($routine_id)
    {
        $startDate = Routine::where('id', $routine_id)->value('created_at');
        $begin = Carbon::create($startDate->year, $startDate->month, $startDate->day);

        $today = Carbon::today();
        $end = $today->copy()->endOfDay();

        $period = new DatePeriod($begin, new DateInterval('P1D'), $end);//$begin以上$rangeEnd未満

        $dbData = [];

        foreach ($period as $date) {
            $range[$date->format("Y-m-d")] = 0;
        }

        $data = Record::where('routine_id', $routine_id)
        ->whereBetween('created_at', [$begin, $end])//$begin以上$dbEnd以下
        ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day'), DB::raw('count(created_at) as count'))
        ->groupBy('day')
        ->get();

        foreach ($data as $val) {
            $dbData[$val->day] = $val->count;
        }

        $data = array_replace($range, $dbData);

        $count = 0;
        $highestCount = 0;
        $today = Carbon::today();
        foreach ($data as $key => $value) {
            $dbDate = new Carbon($key);
            if ($dbDate->eq($today)) {
                if ($value !== 0) {
                    $count++;
                    if ($highestCount < $count) {
                        $highestCount = $count;
                    }
                }
                if ($value === 0) {
                    if ($highestCount < $count) {
                        $highestCount = $count;
                    }
                }
            } elseif ($value !== 0) {
                $count++;
            } elseif ($value === 0) {
                if ($highestCount < $count) {
                    $highestCount = $count;
                    $count = 0;
                }
                $count = 0;
            }
        }

        return [$count, $highestCount];
    }
}
