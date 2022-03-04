<?php


namespace App\Traits\UserTrait;

use App\Scopes\UserScope\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


trait UserTrait
{
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserScope());

        static::creating(function(Model $model)
        {
            $model->applyTenant();
        });
    }

    public function applyTenant()
    {
        $this->setAttribute( 'user_id', Auth::id() );
    }

}
