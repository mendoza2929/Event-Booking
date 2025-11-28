<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserAccount;
use App\Traits\CommonQueryScopes;
class Event extends Model {
    use CommonQueryScopes;
    protected $table = 'events';  
    protected $fillable = [
        'title', 'description', 'date', 'time', 'location', 'created_by'
    ];

	public function creator()
    {
        return $this->belongsTo(UserAccount::class, 'created_by');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function organizer()
    {
        return $this->belongsTo(UserAccount::class, 'organizer_id');
    }

}
