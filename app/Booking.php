<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserAccount;

class Booking extends Model {

    protected $table = 'bookings';  
    protected $fillable = [
        'user_id', 'ticket_id', 'quantity','status'
    ];

	public function user()
    {
        return $this->belongsTo(UserAccount::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

}
