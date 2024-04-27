<?php

namespace App\Models;

use App\Http\Traits\HasProfilePhoto;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'last_name',
        'rut',
        'phone',
        'email',
        'password',
        'profile_id',
        'company_id',
        'parent_user_id',
        'is_active',
        'email_verified_at',
        'free_trial_expired_at',
        'signature_photo_path',
        'company_photo_path',
        'credit_balance',
        'title',
        'oauth_id',
        'oauth_service'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'free_trial_expired_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'parent_user_id');
    }

    public function layers()
    {
        return $this->hasMany(Layer::class);
    }

    public function oauthServices()
    {
        return $this->hasMany(OauthService::class);
    }

    public function operations()
    {
        return $this->belongsToMany(Operation::class)->withTimestamps();
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function isSuperAdminProfile()
    {
        return $this->profile_id == Profile::SUPER_ADMIN;
    }

    public function isOwnerProfile()
    {
        return $this->profile_id == Profile::OWNER;
    }

    public function isUserProfile()
    {
        return $this->profile_id == Profile::USER;
    }

    public function isAuditorProfile()
    {
        return $this->profile_id == Profile::AUDITOR;
    }


    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function getParentID()
    {
        return $this->parent_user_id ? $this->parent_user_id : $this->id;
    }

    public function getCreditBalance()
    {
        $balance = 0;
        if (auth()->user()->isSuperAdminProfile() || auth()->user()->isOwnerProfile()) {
            $balance = auth()->user()->credit_balance;
        } else {
            $balance = auth()->user()->parentUser->credit_balance;
        }
        return $balance;
    }

    public function checkBillingAccess()
    {
        return $this->isSuperAdminProfile() || $this->isOwnerProfile();
    }


    public function isFreeTrialExpired()
    {
        // if (!empty($this->free_trial_expired_at)) {
        //     return $this->free_trial_expired_at->isPast();
        // } else {
        //     return false;
        // }
        return false;
    }

    public function hasUnlimitedBalance()
    {
        return $this->isSuperAdminProfile();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function transactionsIn($from, $to)
    {
        return $this->transactions()->select(DB::raw("EXTRACT(month from created_at) as MONTH, EXTRACT(YEAR FROM created_at) AS YEAR, CONCAT(EXTRACT(month from created_at), '-', EXTRACT(YEAR FROM created_at)) as MON, COUNT(*)"))
            ->where('transaction_type_id', TransactionType::IN)
            ->where('user_id', $this->id)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupByRaw("MONTH, YEAR, MON")
            ->orderByRaw("YEAR, MONTH")->get();
    }

    public function transactionsOut($from, $to)
    {
        return $this->transactions()->select(DB::raw("EXTRACT(month from created_at) as MONTH, EXTRACT(YEAR FROM created_at) AS YEAR, CONCAT(EXTRACT(month from created_at), '-', EXTRACT(YEAR FROM created_at)) as MON, COUNT(*)"))
            ->where('transaction_type_id', TransactionType::OUT)
            ->where('user_id', $this->id)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupByRaw("MONTH, YEAR, MON")
            ->orderByRaw("YEAR, MONTH")->get();
    }

    public function transactionsSuccess($from, $to)
    {
        return $this->transactions()->select(DB::raw("EXTRACT(month from created_at) as MONTH, EXTRACT(YEAR FROM created_at) AS YEAR, CONCAT(EXTRACT(month from created_at), '-', EXTRACT(YEAR FROM created_at)) as MON, COUNT(*)"))
            ->where('transaction_status_id', TransactionStatus::SUCCESS)
            ->where('user_id', $this->id)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupByRaw("MONTH, YEAR, MON")
            ->orderByRaw("YEAR, MONTH")->get();
    }

    public function transactionsFailed($from, $to)
    {
        return $this->transactions()->select(DB::raw("EXTRACT(month from created_at) as MONTH, EXTRACT(YEAR FROM created_at) AS YEAR, CONCAT(EXTRACT(month from created_at), '-', EXTRACT(YEAR FROM created_at)) as MON, COUNT(*)"))
            ->where('transaction_status_id', TransactionStatus::FAILED)
            ->where('user_id', $this->id)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupByRaw("MONTH, YEAR, MON")
            ->orderByRaw("YEAR, MONTH")->get();
    }

    public function transactionsFound($from, $to)
    {
        return $this->transactions()->select(DB::raw("EXTRACT(month from created_at) as MONTH, EXTRACT(YEAR FROM created_at) AS YEAR, CONCAT(EXTRACT(month from created_at), '-', EXTRACT(YEAR FROM created_at)) as MON, COUNT(*)"))
            ->where('data', '!=', "")
            ->where('user_id', $this->id)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupByRaw("MONTH, YEAR, MON")
            ->orderByRaw("YEAR, MONTH")->get();
    }

    public function lastTransactions()
    {
        return $this->transactions()->where('user_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public static function signup($input)
    {

        $v = Validator::make($input, [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'profile_id' => 'required',
            'free_trial_expired_at' => 'required',
            'credit_balance' => 'required',
            'company_name' => 'required',
        ]);

        if ($v->fails()) {
            throw new Exception($v->errors()->first(), 1);
        }

        $company = Company::create([
            'name' => $input['company_name'],
        ]);

        $user = User::create([
            'name' => $input['name'],
            'last_name' => $input['last_name'],
            'email' => mb_strtolower($input['email']),
            'password' => Hash::make($input['password']),
            'profile_id' => $input['profile_id'],
            'is_active' => true,
            'free_trial_expired_at' => $input['free_trial_expired_at'],
            'credit_balance' => $input['credit_balance'],
            'company_id' => $company->id,
        ]);
        if (!empty($input['email_verified_at'])) {
            $user->email_verified_at = $input['email_verified_at'];
        }
        $user->parent_user_id = $user->id;
        $user->save();

        $company->parent_company_id = $company->id;
        $company->parent_user_id = $user->id;
        $company->save();
        self::createDefaultFindingTypes($user->id);
        return $user;
    }

    public static function createDefaultFindingTypes($userId)
    {
        $items = FindingType::where("parent_user_id", null)->get()->toArray();
        foreach ($items as $item) {
            if (FindingType::where('name', $item['name'])->where('parent_user_id', $userId)->first() == null) {
                $item['parent_user_id'] = $userId;
                unset($item['id']);
                FindingType::create($item);
            }
        }
    }
}
