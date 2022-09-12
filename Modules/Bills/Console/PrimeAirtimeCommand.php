<?php

namespace Modules\Bills\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Modules\Bills\Models\Bill;
use Modules\Bills\Processors\PrimeAirtime\PrimeAirtime;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PrimeAirtimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prime-airtime:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to load bill payment from prime airtime api';

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
        $this->info("Processing prime airtime refill");
        $dataServices = $this->populateData();
        $dataServices = array_merge($dataServices, $this->populateAirtime());

        foreach ($dataServices as $service) {
            $serviceResponse = (new PrimeAirtime())->networkValidation($service['category'], $service['number']);
            if (Arr::get($serviceResponse, 'opts.msisdn') == $service['number']) {
                if (!empty(Arr::get($serviceResponse, 'products', []))) {
                    foreach ($serviceResponse['products'] as $product) {
                        $faceValue = $product['face_value'] ?? '';
                        $billName = $service['network'];
                        if ($service['category'] == Bill::MOBILE_DATA) {
                            $billName .= ' '.$faceValue.' '.explode('-', $product['product_id'])[3];
                        }
                        Bill::updateOrCreate([
                            "service_code" => $product['product_id'],
                        ], [
                            "name" => $billName,
                            "vendor" => Bill::PRIME_REFILL_SERVICE,
                            "category" => $service['category'],
                            "group_name" => strtoupper($service['group_name']),
                            "amount" => $service['category'] == Bill::MOBILE_DATA ? $faceValue : 0.00,
                            "fee" => Arr::get($product, 'price'),
                            "status" => true,
                            'image' => Arr::get($service, 'image'),
                        ]);
                    }
                }
            }
        }
        $this->info('The prime airtime bill command was successful!');
    }

    private function populateData(): array
    {
        return [
            [
                'number' => '2348067554321',
                'category' => Bill::MOBILE_DATA,
                'group_name' => 'mtn-data',
                'network' => 'MTN',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/MTN-Airtime.jpg",
            ],
            [
                'number' => '2348094220112',
                'category' => Bill::MOBILE_DATA,
                'group_name' => '9mobile-data',
                'network' => '9mobile',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/9mobile-Airtime.jpg",
            ],[
                'number' => '2348077586157',
                'category' => Bill::MOBILE_DATA,
                'group_name' => 'glo-data',
                'network' => 'GLO',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/GLO-Airtime.jpg",
            ],[
                'number' => '2348029146152',
                'category' => Bill::MOBILE_DATA,
                'group_name' => 'airtel-data',
                'network' => 'AIRTEL',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/Airtel-Airtime.jpg",
            ],
        ];
    }

    private function populateAirtime(): array
    {
        return [
            [
                'number' => '2348067554321',
                'category' => Bill::AIRTIME,
                'group_name' => 'MTN',
                'network' => 'MTN',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/MTN-Airtime.jpg",
            ],
            [
                'number' => '2348094220112',
                'category' => Bill::AIRTIME,
                'group_name' => '9mobile',
                'network' => '9mobile',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/9mobile-Airtime.jpg",
            ],[
                'number' => '2348077586157',
                'category' => Bill::AIRTIME,
                'group_name' => 'GLO',
                'network' => 'GLO',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/GLO-Airtime.jpg",
            ],[
                'number' => '2348029146152',
                'category' => Bill::AIRTIME,
                'group_name' => 'Airtel',
                'network' => 'AIRTEL',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/Airtel-Airtime.jpg",
            ],
        ];
    }
}
