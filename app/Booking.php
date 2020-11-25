<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'booking';

    public $sortOrder = 'asc';
    public $sortEntity = 'booking.id';

    protected $fillable = [
        'transaction_id',
        'transaction_datetime',

        'refund_id',
        'refund_datetime',

        'vehicle_category_id',
        'customer_id',
        'customer_mobile_number',
        'hire_provider_id',
        'hire_vehicle_id',
        'hire_amount',

        'booking_number',
        'transfer_datetime',
        'booking_type',
        'booking_status',

        'from_location',
        'from_lat',
        'from_lon',

        'to_location',
        'to_lat',
        'to_lon',

        'is_return_way',
        'return_datetime',

        'no_of_adult',
        'no_of_children',

        'is_flight',
        'flight_no',

        'is_meeting',
        'passenger_name',

        'is_promo_code',
        'promo_code',

        'requirement',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function homeFrontBookByRoute($inputs = []) {
        $rules = [
            'email' => 'required|email',
            'from_location' => 'required|min:1',
            'vehicle_category' => 'required|numeric|min:1',
            'booking_type' => 'required|in:route,hour',
        ];

        if(isset($inputs['booking_type']) && $inputs['booking_type'] == 'route') {
            $rules['to_location'] = 'required|min:1';
        }
        return validator($inputs, $rules);
    }

    public function frontBookByRoute($inputs = []) {
        $rules = [
            'email' => 'required|email',
            'mobile_number' => 'required|digits:10',
            'from_location' => 'required|min:1',
            'to_location' => 'required|min:1',
            'transfer_date' => 'required|date',
            'transfer_time' => 'required',
            'no_of_adult' => 'required|numeric|min:1|max:100',
            'no_of_children' => 'required|numeric|min:1|max:100',
            'vehicle_category' => 'required|numeric|min:1',
            // 'requirement' => 'required|min:1',
        ];

        if(isset($inputs['is_return_way'])) {
            $rules['return_date'] = 'required|date';
            $rules['return_time'] = 'required';
        }

        if(isset($inputs['is_flight'])) {
            $rules['flight_number'] = 'required|min:1';
        }

        if(isset($inputs['is_meeting'])) {
            $rules['passenger_name'] = 'required|min:1';
        }

        if(isset($inputs['is_promo_code'])) {
            $rules['promo_code'] = 'required|min:1';
        }

        return validator($inputs, $rules);
    }

    public function frontBookPerHour($inputs = []) {
        $rules = [
            'email' => 'required|email',
            'mobile_number' => 'required|digits:10',
            'from_location' => 'required|min:1',
            'to_location' => 'required|min:1',
            'transfer_date' => 'required|date',
            'transfer_time' => 'required',

            'return_date' => 'required|date',
            'return_time' => 'required',

            'no_of_adult' => 'required|numeric|min:1|max:100',
            'no_of_children' => 'required|numeric|min:1|max:100',
            'vehicle_category' => 'required|numeric|min:1',
            // 'requirement' => 'required|min:1',
        ];

        if(isset($inputs['is_flight'])) {
            $rules['flight_number'] = 'required|min:1';
        }

        if(isset($inputs['is_meeting'])) {
            $rules['passenger_name'] = 'required|min:1';
        }

        if(isset($inputs['is_promo_code'])) {
            $rules['promo_code'] = 'required|min:1';
        }

        return validator($inputs, $rules);
    }

    public function bookingNumber() {
        $count = 0;
        $result = $this->orderBy('id', 'desc')->first(['id']);
        if($result) { $count = $result->id; }
        return srNo($count);
    }

    public function pending($customerId)
    {
        $fields = [
            'booking.*',
            'category.category_name',
            \DB::raw('ifnull(count(booking_quotation.id), 0) as total_quotation')
        ];

        return $this
            ->leftJoin('booking_quotation', 'booking_quotation.booking_id', '=', 'booking.id')
            ->leftJoin('category', 'category.id', '=', 'booking.vehicle_category_id')
            ->where('booking.customer_id', $customerId)
            ->where('booking.booking_status', 'pending')
            ->groupBy('booking.id')
            ->get($fields);
    }

    public function upcoming($customerId)
    {
        $fields = [
            'booking.*',
            'category.category_name',
        ];

        return $this
            ->leftJoin('category', 'category.id', '=', 'booking.vehicle_category_id')
            ->where('booking.customer_id', $customerId)
            ->where('booking.booking_status', 'hired')
            ->get($fields);
    }

    public function past($customerId)
    {
        $fields = [
            'booking.*',
            'category.category_name',
        ];

        return $this
            ->leftJoin('category', 'category.id', '=', 'booking.vehicle_category_id')
            ->where('booking.customer_id', $customerId)
            ->whereIn('booking.booking_status', ['completed', 'canceled'])
            ->get($fields);
    }

    public function customerBooking($bookingId, $customerId)
    {
        $fields = [
            'booking.*',
            'category.category_name',
        ];
        return $this
            ->leftJoin('category', 'category.id', '=', 'booking.vehicle_category_id')
            ->where('booking.id', $bookingId)
            ->where('booking.customer_id', $customerId)
            ->first($fields);
    }
}
