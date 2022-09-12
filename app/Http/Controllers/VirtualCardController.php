<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VirtualCard;
use Illuminate\Http\Request;
use App\Services\MonoService;

class VirtualCardController extends Controller
{
    public function __construct()
    {
        $this->monoService = resolve(MonoService::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return VirtualCard::all();
    }

    /**
     * Fund the Wallet and add Money to Card
     *
     * @return \Illuminate\Http\Response
     */
    public function FundWallet(Request $request)
    {
        $validated = $request->validate([
            "amount"=>"required",
            "currency"=>"sometimes"
        ]);
            $link = "";
        if($validated["currency"]){
            $payload = [
                "amount"=>$validated["amount"],
                "currency"=>$validated["currency"]
            ];
            $link = $this->monoService->Fund($payload)["data"]["link"];
            if($link){
                auth()->user()->virtual_card()->latest()->first()->update(["balance"=>$payload["amount"]]);
            }
        }else{
            $payload = [
                "amount"=>$validated["amount"],
                "currency"=> "usd"
            ];
            $link = $this->monoService->Fund($payload)["data"]["link"];
            if($link){
                auth()->user()->virtual_card()->latest()->first()->update(["balance"=>$payload["amount"]]);
            }
        }


        return $this->jsonResponse("Please use the following link to fund the card: ".$link,200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $virtual_account = auth()->user()->virtual_account;

        $payload = [
            	"account_holder"=>$virtual_account->account_holder
        ];
        $virtual_card_id = $this->monoService->CreateVirtualCard($payload)["data"]["id"];

        $virtual_card = $this->monoService->GetVirtualCard($virtual_card_id)["data"];
        $virtual_card["user_id"] = auth()->id();

        VirtualCard::create($virtual_card);

        return $this->createdResponse("Virtual Card created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VirtualCard $virtualCard)
    {
        return $virtualCard;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VirtualCard $virtualCard)
    {
        $validated = $request->validate([
            "disposable"=>['sometimes'],
            "status"=>['required'],
            "type"=>['required'],
            "currency"=>['required'],
            "brand"=>['required'],
            "card_number"=>['required'],
            "card_pan"=>['required'],
            "cvv"=>['required'],
            "expiry_month"=>['required'],
            "expiry_year"=>['required'],
            "last_four"=>['required'],
            "name_on_card"=>['required'],
            "balance"=>['required'],
        ]);
        $virtualCard->update($validated);
        return $this->jsonResponse("Virtual Card updated successfully",200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VirtualCard $virtualCard)
    {
        $virtualCard->delete();
        return $this->jsonResponse("Virtual Card deleted successfully",200);
    }
}
