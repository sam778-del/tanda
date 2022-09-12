<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }

    public function __invoke()
    {
        $orderTransaction = Transaction::totalTransactionPrice();
        $todayTransaction = Transaction::todayTransactionPrice();
        $monthlyTransaction = Transaction::monthlyTransaction();
        $getOrderChart     = $this->getOrderChart(['duration' => 'week']);
        $transaction = Transaction::OrderBy('id', 'DESC')
                        ->whereDate('created_at', Carbon::today()->subDays(7))
                        ->get();
        return view('dashboard', compact('orderTransaction', 'todayTransaction', 'monthlyTransaction', 'transaction', 'getOrderChart'));
    }

    public function themeUpdate(){
        $mode = auth()->user()->mode === 1 ? false : true;
        Admin::where('id', auth()->user()->id)->update([
            'mode' => $mode
        ]);
        return response()->json(['message' => __('Theme updated successfully')], 200);
    }

    public function getOrderChart(array $arrParam)
    {
        $arrDuration = [];
        if($arrParam['duration'])
        {
            if($arrParam['duration'] == 'week')
            {
                $previous_week = strtotime("-2 week +1 day");

                for($i = 0; $i < 14; $i++)
                {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);

                    $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }
        $arrTask = [];
        foreach($arrDuration as $date => $label)
        {
            $data               = Transaction::select(DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $data->total;
        }

        return $arrTask;
    }

}
