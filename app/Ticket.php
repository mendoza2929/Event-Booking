<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

    protected $table = 'tickets';  
    protected $fillable = ['event_id', 'type', 'price', 'quantity'];

	public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

}
