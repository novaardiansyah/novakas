<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
  use HasFactory;
  protected $table = 'payments';
  protected $guarded = ['id'];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function payment_account(): BelongsTo
  {
    $relationship = $this->belongsTo(PaymentAccount::class);
    if (auth()->user()->checkPermissionTo('*')) return $relationship;
    return $relationship->where('user_id', auth()->user()->id);
  }

  public function payment_type(): BelongsTo
  {
    return $this->belongsTo(PaymentType::class, 'type_id');
  }
}
