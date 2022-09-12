<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhysicalCard;
use App\Services\MonoService;
//Mono Changes
class PhysicalCardController extends Controller
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
        return PhysicalCard::all();
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
            	"account"=>$virtual_account->account_holder
        ];


        $physical_card_id = $this->monoService->CreatePhysicalCard($payload)["data"]["id"];

        $physical_card = $this->monoService->GetPhysicalCard($physical_card_id)["data"];
        $physical_card["user_id"] = auth()->id();

        PhysicalCard::create($physical_card);

        return $this->createdResponse("Physical Card created successfully");
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
                auth()->user()->physical_card()->latest()->first()->update(["balance"=>$payload["amount"]]);
            }
        }else{
            $payload = [
                "amount"=>$validated["amount"],
                "currency"=> "NRG"
            ];
            $link = $this->monoService->Fund($payload)["data"]["link"];
            if($link){
                auth()->user()->physical_card()->latest()->first()->update(["balance"=>$payload["amount"]]);
            }
        }


        return $this->jsonResponse("Please use the following link to fund the card: ".$link,200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PhysicalCard $physicalCard)
    {
        return $physicalCard;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhysicalCard $physicalCard)
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
        $physicalCard->update($validated);
        return $this->jsonResponse("Virtual Card updated successfully",200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhysicalCard $physicalCard)
    {
        $physicalCard->delete();
        return $this->jsonResponse("Virtual Card deleted successfully",200);
    }
}
