<?php

namespace Modules\Bills\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Bills\Models\Bill;
use Modules\Bills\Processors\Airvend\Airvend;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AirvendServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $endpoint = "";
        $vendor = $this->argument("vendor") ?? "airvend";
        $endpoint = config("airvend.services.get_billers");
        $data = [
            "ref" => ""
        ];
        if (!empty($endpoint)) {
            $airVend = resolve(Airvend::class);
            $hashParameter = json_encode($data) . $airVend->apiKey;
            $hash = hash("sha512", $hashParameter);
            $headers = [
                "username: $airVend->username",
                "password: $airVend->password",
                "hash: $hash"
            ];

            $billers = Http::withHeaders($headers)
                ->post($endpoint, $data);

            if (!empty(Arr::get($billers, 'details.message')) && is_array($billers["details"]["message"])) {
                $i = 1;
                foreach (@$billers["details"]["message"] as $biller) {
                    //$billerItem = BillerItem::query()->where("name", $biller["Service"])->first();
                    Bill::query()->firstOrCreate([
                        "name" => $biller["Service"],
                        "service_code" => "SC$i"
                    ], [
                        "name" => $biller["Service"],
                        "service_code" => "SC$i",
                        "type_id" => $biller["Type"],
                        "network_id" => $biller["NetworkID"],
                        "is_product" => $biller["Product"],
                        "is_verify" => $biller["verify"]
                    ]);
                    $i++;
                }

                //Update Biller Name
                $discos = config("disco");
                foreach ($discos as $disco) {
                    $name = $disco["name"];
                    $image = $disco["image"];
                    foreach ($disco["billers"] as $biller) {
                        $billerName = $biller["name"];
                        $service = Bill::query()->where("name", $billerName)->first();
                        if ($service) {
                            $service->update([
                                "image" => $image
                            ]);
                        }
                    }
                }
            }

        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
