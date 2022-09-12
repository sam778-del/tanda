<?php

namespace Modules\Bills\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Modules\Bills\Models\Bill;
use Modules\Bills\Processors\Vtpass\Vtpass;

class VtpassCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtpass:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Processing vtpass refill");
        $services = $this->serviceList();
        foreach ($services as $key => $service) {
            $serviceResponse = (new Vtpass())->fetchServiceVariation($service['value']);
            if (Arr::get($serviceResponse, 'response_description') === '000') {
                if (!empty(Arr::get($serviceResponse, 'content.varations'))) {
                    $productDetails = Arr::get($serviceResponse, 'content.varations');
                    foreach ($productDetails as $product) {
                        Bill::updateOrCreate([
                            'service_code' => $product['variation_code']
                        ], [
                            'vendor' => Bill::VTUPASS_REFILL_SERVICE,
                            'name' => $product['name'],
                            'amount' => $product['variation_amount'],
                            'category' => $service['category'],
                            'group_name' => strtoupper($service['value']),
                            "status" => true,
                            'image' => Arr::get($service, 'image'),
                        ]);
                    }
                }
            }
        }
        $this->info('The vtpass airtime bill command was successful!');
    }

    public function serviceList(): array
    {
        return [
            [
                'category' => Bill::MOBILE_DATA,
                'value' => 'airtel-data',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/Airtel-Airtime.jpg",
            ],
            [
                'category' => Bill::MOBILE_DATA,
                'value' => 'mtn-data',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/MTN-Airtime.jpg",
            ],
            [
                'category' => Bill::MOBILE_DATA,
                'value' => 'glo-data',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/GLO-Airtime.jpg",
            ],
            [
                'category' => Bill::MOBILE_DATA,
                'value' => 'etisalat-data',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/9mobile-Airtime.jpg",
            ],
            [
                'category' => Bill::MOBILE_DATA,
                'value' => 'smile-direct',
                'image' => 'https://sandbox.vtpass.com/resources/products/200X200/Smile-Payment.jpg',
            ],
            [
                'category' => Bill::TV_SUBSCRIPTION,
                'value' => 'dstv',
                'image' => 'https://sandbox.vtpass.com/resources/products/200X200/Pay-DSTV-Subscription.jpg',
            ],
            [
                'category' => Bill::TV_SUBSCRIPTION,
                'value' => 'gotv',
                'image' => 'https://sandbox.vtpass.com/resources/products/200X200/Gotv-Payment.jpg',
            ],
            [
                'category' => Bill::TV_SUBSCRIPTION,
                'value' => 'startimes',
                'image' => "https://sandbox.vtpass.com/resources/products/200X200/Startimes-Subscription.jpg",
            ],
            [
                'category' => Bill::ELECTRICITY,
                'value' => 'ikeja-electric',
                'image' => "https://sandbox.vtpass.com/resources/products/200X200/Ikeja-Electric-Payment-PHCN.jpg",
            ],
            [
                'category' => Bill::ELECTRICITY,
                'value' => 'eko-electric',
                'image' => "https://sandbox.vtpass.com/resources/products/200X200/Eko-Electric-Payment-PHCN.jpg",
            ],
            [
                'category' => Bill::ELECTRICITY,
                'value' => 'abuja-electric',
                'image' => "https://sandbox.vtpass.com/resources/products/200X200/Abuja-Electric.jpg"
            ],
            [
                'category' => Bill::ELECTRICITY,
                'value' => 'jos-electric',
                'image' => "https://sandbox.vtpass.com/resources/products/200X200/Jos-Electric-JED.jpg"
            ],
            [
                'category' => Bill::ELECTRICITY,
                'value' => 'kaduna-electric',
                'image' => "https://sandbox.vtpass.com/resources/products/200X200/Kaduna-Electric-KAEDCO.jpg",
            ],
            [
                'category' => Bill::ELECTRICITY,
                'value' => 'kano-electric',
                'image' =>  "https://sandbox.vtpass.com/resources/products/200X200/Kano-Electric.jpg"
            ],
            [
                'category' => Bill::ELECTRICITY,
                'value' => 'portharcourt-electric',
                'image' => "https://sandbox.vtpass.com/resources/products/200X200/Port-Harcourt-Electric.jpg",
            ],


            [
                'category' => Bill::ELECTRICITY,
                'value' => 'ibadan-electric',
                'image' => "https://sandbox.vtpass.com/resources/products/200X200/"
            ],
        ];
    }



    private function availableService(): array
    {
        return [
            'airtime',
            'data',
            'tv-subscription',
            'electricity-bill',
        ];
    }
}
