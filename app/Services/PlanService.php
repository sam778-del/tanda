<?php

namespace App\Services;

use App\Models\Plan;
use Carbon\Carbon;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\Models\Employer;
use App\Models\EmployeeInvoice;

class PlanService
{
    public function assignFreePlan(int $user_id){
        try {
            $user = Employer::find($user_id);
            $user->update([
                'plan_expiry_status' => true,
                'plan_expiry_date' => $this->getDate($this->freePlan()->period),
                'plan_id' => $this->freePlan()->id
            ]);

            $customer = new Party([
                'name' => $user->first_name.' '.$user->last_name,
            ]);
            $items = [
                (new InvoiceItem())
                    ->title($this->freePlan()->name)
                    // ->description('1 month subscription for '.$this->freePlan()->name)
                    ->pricePerUnit($this->freePlan()->price)
                    ->quantity(1)
            ];
            $invoice = Invoice::make('receipt')
                        ->series('BIG')
                        // ->status(__('invoices::invoice.paid'))
                        ->buyer($customer)
                        ->date(now())
                        ->dateFormat('m/d/Y')
                        ->currencySymbol('₦')
                        ->currencyCode('NGN')
                        ->currencyFormat('{SYMBOL}{VALUE}')
                        ->currencyThousandsSeparator('.')
                        ->currencyDecimalPoint(',')
                        ->filename($customer->name)
                        ->addItems($items)
                        ->logo(public_path('logo/s-logo.png'))
                        ->save('public');
            $link = $invoice->url();

            $invoiceID = strtoupper(str_replace('.', '', uniqid('', true)));
            $einvoice = new EmployeeInvoice();
            $einvoice->invoice_id = $invoiceID;
            $einvoice->employer_id = $user->id;
            $einvoice->status = $user->plan_expiry_status;
            $einvoice->url = $link;
            $einvoice->plan_id = $user->plan_id;
            $einvoice->save();
            return true;
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
    }

    public function freePlan(){
        $plan = Plan::where('name', 'Free')->first(['id', 'name', 'period', 'price']);
        if(!empty($plan))
        {
            return $plan;
        }else{
            abort(404, "Free plan not found");
        }
    }

    public function getDate(String $date)
    {
        switch ($date) {
            case $date == 'monthly':
                $plan_expiry_date = Carbon::now()->addMonth(1);
                break;

            default:
                $plan_expiry_date = NULL;
                break;
        }
        return $plan_expiry_date;
    }
}
