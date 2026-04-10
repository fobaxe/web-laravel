<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['client', 'sujet', 'description', 'priorite', 'statut', 'due', 'user_id', 'projet_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function tempsPasses()
    {
        return $this->hasMany(TempsPasse::class);
    }

    // Total du temps passé sur ce ticket en minutes
    public function totalMinutes(): int
    {
        return $this->tempsPasses->sum('duree');
    }

    // Total formaté en "Xh Ymin"
    public function totalTemps(): string
    {
        $total = $this->totalMinutes();
        $h     = intdiv($total, 60);
        $min   = $total % 60;
        return $h > 0 ? "{$h}h {$min}min" : "{$min}min";
    }
}
