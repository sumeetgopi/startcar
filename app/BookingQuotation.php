<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingQuotation extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'booking_quotation';

    public $sortOrder = 'asc';
    public $sortEntity = 'booking_quotation.id';

    protected $fillable = [
        'booking_id',
        'provider_id',
        'vehicle_id',
        'quotation_date',
        'quotation_amount',
        'is_canceled',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function bookingQuotation($bookingId)
    {
        $fields = [
            'booking_quotation.*'
        ];
        
        return $this
            ->where('booking_quotation.booking_id', $bookingId)
            ->get($fields);
    }
}
