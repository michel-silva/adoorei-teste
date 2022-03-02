<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'history',
        'status',
        'last_update',
        'user_id'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($track) {
            $track->user_id = Auth::id();
        });
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('test', function(Builder $builder) {
           $builder->where('user_id', Auth::id());
        });
    }


    static function getStatusTypes()
    {
        return [
            '' => 'Aguardando Postagem',
            'Objeto postado' => 'Postado',
            'Objeto em trânsito - por favor aguarde' => 'Em trânsito',
            'Objeto aguardando retirada no endereço indicado' => 'Retirada',
            'Objeto saiu para entrega ao destinatário' => 'Saiu para Entrega',
            'Objeto entregue ao destinatário' => 'Entregue',
            'Objeto devolvido ao país de origem' => 'Devolvido'
        ];
    }

    protected $cast = [
        'history' => 'array'
    ];

}
