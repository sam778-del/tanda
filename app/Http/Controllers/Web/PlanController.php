<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInvoice;
use App\Models\Plan;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employer');
    }

    public function index(Request $request)
    {
        $employer = $request->user();
        $data = [
            "plan" => [
                "plan_name" => !empty($employer->plan->name) ? $employer->plan->name : null,
                "period" => !empty($employer->plan->period) ? $employer->plan->period : null,
                "price_per_employee" => !is_null($employer->plan->price) ? "₦ {$employer->plan->price} /month": null,
                "employees" => count($employer->employees),
                "next_payment" => !is_null($employer->plan->price) ? "₦ ".$employer->plan->price * count($employer->employees) : null,
            ],
            "invoices" => $this->getAllInvoice($employer->id)
        ];
        return response()->json($data, 200);
    }

    public function getAllInvoice(int $employer_id)
    {
        return EmployeeInvoice::orderBy('id', 'DESC')
            ->where('employer_id', $employer_id)
            ->get(['invoice_id', 'status', 'url', 'created_at']);
    }

    public function allPlan()
    {
        return response()->json(
            Plan::orderBy('id', 'ASC')->get(['id', 'name', 'price', 'period']),
        200);
    }
}
