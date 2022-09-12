<?php

namespace Modules\Bills\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    // Refill Services
    const ELECTRICITY = 'Electricity/Utility Bills';
    const TV_SUBSCRIPTION = 'Cable Bill Payment';
    const AIRTIME = 'Airtime Service';
    const MOBILE_DATA = 'Mobile Data Service';
    const INTERNET_SERVICE = 'Internet Service';
    const EDUCATION = 'Education Bill Payment';

    // Vendors
    const VTUPASS_REFILL_SERVICE = "vtupass";
    const PRIME_REFILL_SERVICE = "prime";
    const AIRVEND_SERVICE = "prime";

    protected $fillable = [
        "name",
        "vendor",
        "category",
        "group_name",
        "service_code",
        "image",
        "amount",
        "fee",
        "description",
        "status",
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected static function newFactory()
    {
        return \Modules\Bills\Database\factories\ServiceFactory::new();
    }

    public static function type()
    {
        return collect([
            [
                "category" => self::AIRTIME,
                "slug" => 'airtime-bill-service',
                "vendor" => self::PRIME_REFILL_SERVICE
            ],
            [
                "category" => self::MOBILE_DATA,
                "slug" => 'mobile-data-bill-service',
                "vendor" => self::PRIME_REFILL_SERVICE
            ],
            [
                "category" => self::TV_SUBSCRIPTION,
                "slug" => 'cable-bill-service',
                "vendor" => self::VTUPASS_REFILL_SERVICE
            ],

            [
                "category" => self::INTERNET_SERVICE,
                "slug" => 'internet-bill-service',
                "vendor" => self::VTUPASS_REFILL_SERVICE
            ],
            [
                "category" => self::ELECTRICITY,
                "slug" => 'electricity-bill-service',
                "vendor" => self::VTUPASS_REFILL_SERVICE
            ],
        ]);
    }

    public function serviceTransactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BillTransaction::class);
    }
}
