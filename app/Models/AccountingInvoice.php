<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingInvoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'related_type',
        'related_id',
        'amount_total',
        'status',
        'due_date',
        'paid_date',
        'pdf_path'
    ];

    protected $casts = [
        'amount_total' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relatedOrder()
    {
        return $this->morphTo('related');
    }
}
