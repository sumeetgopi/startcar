<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, Utility;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rozarpay_customer_id', 
        'user_type',
        'name',
        'email', 
        'fcm_token', 
        'status', 
        'password', 
        'mobile_number',
        'otp_code',
        'forgot_otp',
        'device_type',
        'cashback_amount',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function scopeCustomer($query) {
        return $query->where('users.user_type', 'customer');
    }

    public function scopeAgency($query) {
        return $query->where('users.user_type', 'agency');
    }

    public function findCustomer($email)
    {
        return $this
            ->where('users.email', $email)
            ->customer()
            ->first();
    }

    public function findAgency($mobileNumber)
    {
        return $this
            ->where('users.mobile_number', $mobileNumber)
            ->agency()
            ->first();
    }

    public function getUser($id, $userType)
    {
        $fields = ['users.*'];
        return $this
            ->where('users.id', $id)
            ->where('users.user_type', $userType)
            ->first($fields);
    }

    public function getCustomer($customerId) 
    {
        $fields = ['users.*'];
        return $this
            ->where('users.id', $customerId)
            ->first($fields);
    }

    public function agencyVerify($mobileNumber, $otpCode)
    {
        return $this
            ->where('users.mobile_number', $mobileNumber)
            ->where('users.otp_code', $otpCode)
            ->agency()
            ->first();
    }

    public function customerVerify($email, $otpCode)
    {
        return $this
            ->where('users.email', $email)
            ->where('users.otp_code', $otpCode)
            ->customer()
            ->first();
    }

    public function scopeActive($query) {
        return $query->where('users.status', 1);
    }

    public function getCustomerMobile() 
    {
        return $this
            ->where('users.id', '!=', '1')
            ->active()
            ->get(['mobile_number']);
    }

    public function getCustomerFcmToken() 
    {
        return $this
            ->where('users.id', '!=', '1')
            ->where('users.fcm_token', '!=', '')
            ->active()
            ->get(['fcm_token']);
    }

    public function customerMobileService($heading = false) {
        $result = $this
            ->where('users.id', '!=', '1')
            ->active()
            ->get(['mobile_number', 'name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $name = ($row->name != '') ? ' - ' . $row->name : '';
                $service[$row->mobile_number] = $row->mobile_number . $name;
            }
        }
        return $service;
    }

    public function customerFcmTokenService($heading = false) {
        $result = $this
            ->where('users.id', '!=', '1')
            ->where('users.fcm_token', '!=', '')
            ->active()
            ->get(['fcm_token', 'mobile_number', 'name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $name = ($row->name != '') ? ' - ' . $row->name : '';
                $service[$row->fcm_token] = $row->mobile_number . $name;
            }
        }
        return $service;
    }

    public function updateUserCashback($customerId, $cashbackAmount = 0)
    {
        $result = $this->where('users.id', $customerId)->first();
        if($result) {
            $cashbackAmount = $result->cashback_amount + $cashbackAmount;
            $this->store(['cashback_amount' => $cashbackAmount], $customerId);
        }
    }

    public function getCustomerByFcm($fcm = [])
    {
        return $this
            ->where('users.id', '!=', '1')
            ->whereIn('users.fcm_token', $fcm)
            ->active()
            ->get(['mobile_number']);
    }
    
    public function totalAppDownload($search = [])
    {
        $filter = 1;

        if(isset($search['from_date']) && $search['from_date'] == '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and users.created_at >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'";
        }

        if(isset($search['from_date']) && $search['from_date'] != '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and (users.created_at >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'  and users.created_at <= '" . addslashes(dateFormat($search['to_date'], 'Y-m-d 23:59:59')) . "')";
        }

        return $this
            ->whereRaw($filter)
            ->where('users.id', '!=', '1')
            ->where('users.fcm_token', '!=', '')
            ->active()
            ->count();
    }

    public function totalClientRegistered($search = [])
    {
        $filter = 1;

        if(isset($search['from_date']) && $search['from_date'] == '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and users.created_at >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'";
        }

        if(isset($search['from_date']) && $search['from_date'] != '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and (users.created_at >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'  and users.created_at <= '" . addslashes(dateFormat($search['to_date'], 'Y-m-d 23:59:59')) . "')";
        }

        return $this
            ->whereRaw($filter)
            ->where('users.id', '!=', '1')
            ->count();
    }

    public function loginReport($search = [])
    {
        $filter = 1;
        if(isset($search) && count($search) > 0)
        {
            if((isset($search['from_date']) && $search['from_date'] != '') && (isset($search['to_date']) && $search['to_date'] != '')) {
                $filter .= " and (users.updated_at >= '" . dateFormat($search['from_date'], 'Y-m-d 00:00:00')
                    . "' and users.updated_at <= '" . dateFormat($search['to_date'], 'Y-m-d 23:59:59') . "')";
            }
            else {
                $filter .= (isset($search['from_date']) && $search['from_date'] != '') ?
                    " and users.updated_at >= '" . dateFormat($search['from_date'], 'Y-m-d 00:00:00') . "'" : '';

                $filter .= (isset($search['to_date']) && $search['to_date'] != '') ?
                    " and users.updated_at <= '" . dateFormat($search['to_date'], 'Y-m-d 23:59:59') . "'" : '';
            }

            $filter .= (isset($search['keyword']) && $search['keyword'] != '') ? 
                " and (users.name like '%" . addslashes($search['keyword']) . "%' 
                or users.mobile_number like '%" . addslashes($search['keyword']) . "%')" : '';
        }   

        $fields = [
            'users.*'
        ];

        return $this
            ->where('users.id', '!=', '1')  
            ->whereRaw($filter)
            ->get($fields);
    }

    public function customerEmailExist($email)
    {
        return $this
            ->where('users.email', $email)
            ->where('users.user_type', 'customer')
            ->first();
    }

    public function customerEmailOtpExist($email, $otpCode)
    {
        return $this
            ->where('users.email', $email)
            ->where('users.forgot_otp', $otpCode)
            ->where('users.user_type', 'customer')
            ->first();
    }
}
