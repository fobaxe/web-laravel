<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $fillable = ['nom', 'description', 'client', 'priorite', 'statut', 'due', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Total du temps passé sur tous les tickets du projet en minutes
    public function totalMinutes(): int
    {
        return $this->tickets->sum(fn($t) => $t->totalMinutes());
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
