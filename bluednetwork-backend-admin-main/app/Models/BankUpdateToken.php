<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankUpdateToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        
     ];
  
     /**
      * @return bool
      */
    public function tokenExpired()
    {
        if ($this->created_at->addHours(6)->isPast()) {
           return true;
        }
        return false;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
