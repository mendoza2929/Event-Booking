<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

    protected $table = 'payments';  
    protected $fillable = [
        'booking_id', 'amount', 'status'
    ];

	public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

}
