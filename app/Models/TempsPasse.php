<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempsPasse extends Model
{
    protected $table = 'temps_passes';

    protected $fillable = ['ticket_id', 'user_id', 'date', 'duree', 'commentaire'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Retourne la durée formatée en "Xh Ymin"
    public function dureeFormatee(): string
    {
        $h   = intdiv($this->duree, 60);
        $min = $this->duree % 60;
        return $h > 0 ? "{$h}h {$min}min" : "{$min}min";
    }
}
