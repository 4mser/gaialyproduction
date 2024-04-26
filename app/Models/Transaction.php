<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Transaction extends Model
{

    const TRANSACTION_DIR = 'transactions';

    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'transaction_type_id',
        'transaction_status_id',
        'description',
        'data',
        'before_credit_balance',
        'current_credit_balance',
        'credit',
    ];

    protected $casts = [
        'data' => 'object',
    ];

    public static function pagination($params)
    {

        $transactions = self::where(function ($query) use ($params) {
            $query->where('user_id', auth()->user()->id);
            if ($params['search'] !== '') {
                $query->where(function ($qry) use ($params) {
                    $qry->orWhere('uuid', 'ilike', '%' . $params['search'] . '%');
                    $qry->orWhere('description', 'ilike', '%' . $params['search'] . '%');
                });
            }
        })->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        // $transactions->dd();
        return $transactions->paginate($params['perPage']);
    }

    public static function register($inputs)
    {
        $rules = [
            'user' => 'required',
            'credit' => 'required',
            'typeId' => 'required',
            'statusId' => 'required',
            'description' => 'required',
        ];

        $v = Validator::make($inputs, $rules);

        if ($v->fails()) throw new Exception($v->errors()->first(), true);

        $beforeCreditBalance = $inputs['user']->credit_balance;
        $inputs['user']->credit_balance += $inputs['credit'];
        $inputs['user']->save();
        $currentCreditBalance = $inputs['user']->credit_balance;

        $transaction = self::create([
            'uuid' => Str::uuid(),
            'description' => $inputs['description'],
            'before_credit_balance' => $beforeCreditBalance,
            'current_credit_balance' => $currentCreditBalance,
            'credit' => $inputs['credit'],
            'user_id' => $inputs['user']->id,
            'transaction_type_id' => $inputs['typeId'],
            'transaction_status_id' => $inputs['statusId'],
            'data' => empty($inputs['data']) ? null : $inputs['data'],
        ]);

        return $transaction;
    }

    public static function in($inputs)
    {
        $inputs['typeId'] = TransactionType::IN;
        if (empty($inputs['statusId'])) $inputs['statusId'] = TransactionStatus::SUCCESS;
        return self::register($inputs);
    }

    public static function out($inputs)
    {
        $inputs['credit'] = abs($inputs['credit']) * -1;
        $inputs['typeId'] = TransactionType::OUT;
        if (empty($inputs['statusId'])) $inputs['statusId'] = TransactionStatus::SUCCESS;
        if ($inputs['user']->unlimited_balance)
            $inputs['credit'] = 0;

        return self::register($inputs);
    }

    public function type()
    {
    }

    public function status()
    {
    }
}
