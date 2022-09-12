<?php

namespace App\Models;

use App\Jobs\SmsOtpJob;
use App\Traits\UtilityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\Merchant\Models\Merchant;
use Laravel\Passport\HasApiTokens;
use Modules\Bills\Models\BillTransaction;
use Modules\Savings\Models\SavingsWallet;
use Modules\Savings\Models\SmartGoal;
use Modules\Savings\Models\SmartRule;
use Modules\Wallet\Models\UserBankAccount;
use Modules\Wallet\Models\UserCard;
use Modules\Wallet\Models\UserWallet;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens, UtilityTrait;

    const ENABLE = "ENABLE";
    const DISABLE = "DISABLE";
    const GOOGLE = 'GOOGLE';
    const FACEBOOK = 'FACEBOOK';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "phone_no",
        "email",
        "password",
        "status",
        "blocked_at",
        "status",
        "email_verified_at",
        'referrer_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'referral_code',
        'account_info'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        if (Hash::needsRehash($value)) {
            $value = Hash::make($value);
        }
        $this->attributes['password'] = $value;
    }

    public function setPhoneNoAttribute($value)
    {
        $this->attributes['phone_no'] = $this->formatPhoneNumber($value);
//        $this->attributes['phone_no'] = PhoneNumber::make($value, 'NG')->formatE164();
    }

    public function getAccountInfoAttribute()
    {
        return [
            'is_mono_active' => $this->monoCredentials()->exists() ? true : false,
            'is_card_active' => $this->userCards()->where('is_default', true)->exists() ?? false,
            'is_bank_account_active' => $this->bankAccounts()->exists() ? true : false

        ];
    }

    public function getReferralCodeAttribute()
    {
        return $this->phone_no;
    }

    /**
     * A user has a referrer.
     *
     * @return BelongsTo
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    /**
     * A user has many referrals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(UserBankAccount::class);
    }

    public function tandaNotifications(): HasMany
    {
        return $this->hasMany(TandaNotification::class);
    }

    public function bankStatements(): HasMany
    {
        return $this->hasMany(BankStatement::class);
    }

    public function serviceTransactions(): HasMany
    {
        return $this->hasMany(BillTransaction::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }


    protected static function boot()
    {
        parent::boot();
        static::created(function (User $user) {
//            $numberOfDigits = 4;
//            $activationCode = substr(str_shuffle("0123456789"), 0, $numberOfDigits);
//            Cache::put($user->email . "_activation_code", $activationCode);
//            try {
//                $text = "Tanda account verification pin: " . $activationCode;
//                Cache::put($user->email . "_welcome_text", $text, now()->addHours(1));
//                dispatch(new SmsJob($user->email, $text));
//            } catch (\Exception $exception) {
//                logger($exception->getMessage());
//            }
            try {
                $user->unique_id = time() - mt_rand(1000, 9999);
                $adminEmails = config("tanda.admin_emails", []);
                //assign role to user
                $role = Role::query()->firstOrCreate([
                    "name" => "customer"
                ], [
                    "name" => "customer",
                    "guard_name" => "api"
                ]);
                if ($role) {
                    $roleId = $role->id;
                    if (in_array($user->email, $adminEmails)) {
                        $adminRole = Role::query()->firstOrCreate([
                            "name" => "admin"
                        ], [
                            "name" => "admin",
                            "guard_name" => "web"
                        ]);
                        $roleId = $adminRole->id;
                    }
                    ModelRole::firstOrCreate([
                        'role_id' => $roleId,
                        'model_type' => User::class,
                        'model_id' => $user->id
                    ], [
                        'role_id' => $roleId,
                        'model_type' => User::class,
                        'model_id' => $user->id
                    ]);
                }
            } catch (\Exception $exception) {
                logger($exception->getMessage());
            }
        });
    }

    public function merchant(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Merchant::class);
    }

    public function stackers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserStaker::class, 'stacker_id');
    }

    public function userStackers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserStaker::class, 'user_id');
    }

    public function mobileCredential(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserMobileCredential::class);
    }

    public function monoCredentials(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MonoUser::class);
    }

    public function wallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserWallet::class);
    }

    public function lockWallet()
    {
        $wallet = $this->wallet()->lockForUpdate()->first();
        $this->setRelation('wallet', $wallet);
        return $wallet;
    }

    public function smartGoal(): HasMany
    {
        return $this->hasMany(SmartGoal::class);
    }

    public function smartRule(): HasMany
    {
        return $this->hasMany(SmartRule::class);
    }

    public function savingsWallet(): HasMany
    {
        return $this->hasMany(SavingsWallet::class);
    }

    public function lockSavingWallet($id)
    {
        $savingsWallet = $this->savingsWallet()->lockForUpdate()->find($id);
        $this->setRelation('savings_wallet', $savingsWallet);
        return $savingsWallet;
    }

    public function userCards()
    {
        return $this->hasMany(UserCard::class);
    }

    public function virtual_account(){
        return $this->hasOne(VirtualAccount::class);
    }

    public function virtual_card(){
        return $this->hasOne(VirtualCard::class);
    }

    public function physical_card(){
        return $this->hasOne(PhysicalCard::class);
    }
}
