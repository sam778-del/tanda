<?php

namespace App\Console\Commands;

use App\Models\Bank;
use App\Models\State;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppSetUpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup
                            {--fresh= : Drop DB and run fresh migration}
                            {--seed= : Create a database seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
//        Artisan::call("cache:clear");
//        Artisan::call("view:clear");
//        Artisan::call("config:cache");
        Artisan::call("optimize:clear");

        $fresh = $this->option('fresh');
        $seed = $this->option('seed');

        if (!empty($fresh) and $fresh == "yes") {
            Artisan::call("migrate:fresh");
            $this->info("Installing  Passport");
            Artisan::call('passport:install');
        }

        $roles = Role::all();
        if ($roles->count() <= 0) {
            foreach (config("myroles") as $key => $role) {
                Role::firstOrCreate([
                    "name" => $key,
                    "guard_name" => $role,
                ]);
            }
        }

        $permissions = Permission::all();
        $permissionsArray = [];
        if ($permissions->count() <= 0) {
            foreach (config("mypermissions") as $key => $permission) {
                $data = Permission::firstOrCreate([
                    "name" => $permission
                ]);
                $permissionsArray[] = $data->id;
            }

            // Assign permissions to super admin
            $role = Role::where(['name' => 'super_admin'])->first();
            if (!empty($permissionsArray)) {
                $role->syncPermissions($permissionsArray);
            }
        }

        // Storage link
        if (env("STORAGE_LINK")) {
            Artisan::call("storage:link");
        }

        /*Create Banks*/
//        if (Bank::query()->count() <= 0) {
//            $banks = json_decode(file_get_contents(public_path("banks.json")), true);
//            foreach ($banks as $bankCode => $bankName) {
//                Bank::query()->create([
//                    'bank_name' => $bankName,
//                    'bank_code' => $bankCode
//                ]);
//            }
//        }
        /*End Create Bank*/


        /*Create States and Lgas*/
//        $states = config('states');
//        if (State::query()->count() <= 0) {
//            foreach ($states['data'] as $state) {
//                $newState = State::create([
//                    'name' => $state['name'],
//                    'capital' => $state['capital']
//                ]);
//                foreach ($state['lga'] as $lga) {
//                    $newState->lgas()->create([
//                        'name' => $lga
//                    ]);
//                }
//            }
//        }
        /*End Create states and Lgas*/


        /*Create User*/



        /*Create Admin Role*/
//        $role = Role::query()->where("name", "admin")->first();
//        if (empty($role)) {
//            $adminRole = Role::query()->firstOrCreate([
//                "name" => "admin"
//            ], [
//                "name" => "admin",
//                "guard_name" => "web"
//            ]);
//        }
        /*Create Admin Role*/


        /*Create Admin*/


        /*End create user*/

        if (!empty($seed) and $seed == "yes") {
            $this->info("Installing  database seeds");
            Artisan::call("db:seed");
            Artisan::call("prime-airtime:setup");
            Artisan::call("vtpass:setup");
        }

        $this->info("DONE");
    }
}
