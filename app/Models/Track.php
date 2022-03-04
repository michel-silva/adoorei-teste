<?php

namespace App\Models;

use App\Traits\UserTrait\UserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory, UserTrait;

    protected $fillable = [
        'tracking_number',
        'status',
        'last_update',
        'user_id'
    ];

    public function events()
    {
        return $this->hasMany(TrackEvent::class);
    }

    static function getStatusTypes()
    {
        return [
            '' =>
            [
                'short_status' => 'Aguardando Postagem',
                'icon-dashboard' => 'fa-solid fa-truck-fast',
                'background-color-card' => 'bg-blue-600'
            ],
            'Objeto postado' =>
            [
                'short_status' => 'Postado',
                'icon-dashboard' => 'fa-solid fa-truck-fast',
                'background-color-card' => 'bg-blue-600'
            ],
            'Objeto em trânsito - por favor aguarde' =>
            [
                'short_status' => 'Em trânsito',
                'icon-dashboard' => 'fa-solid fa-truck-fast',
                'background-color-card' => 'bg-blue-600'
            ],
            'Objeto aguardando retirada no endereço indicado' =>
            [
                'short_status' => 'Retirada',
                'icon-dashboard' => 'fa-solid fa-truck-fast',
                'background-color-card' => 'bg-blue-600'
            ],
            'Objeto saiu para entrega ao destinatário' =>
            [
                'short_status' => 'Saiu para Entrega',
                'icon-dashboard' => 'fa-solid fa-truck-fast',
                'background-color-card' => 'bg-blue-600'
            ],
            'Objeto entregue ao destinatário' =>
            [
                'short_status' => 'Entregue',
                'icon-dashboard' => 'fa-solid fa-truck-fast',
                'background-color-card' => 'bg-blue-600'
            ],
            'Objeto devolvido ao país de origem' =>
            [
                'short_status' => 'Devolvido',
                'icon-dashboard' => 'fa-solid fa-truck-fast',
                'background-color-card' => 'bg-blue-600'
            ],

            'Objeto recebido pelos Correios do Brasil' =>
            [
                'short_status' => 'Recebido no Brasil',
                'icon-dashboard' => 'fa-solid fa-truck-fast',
                'background-color-card' => 'bg-blue-600'
            ] //status não previsto no escopo do teste
        ];
    }
}
