<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CooperativaScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        if (! $user->cooperativa_id) {
            return;
        }

        if ($model instanceof \App\Models\Bus || $model instanceof \App\Models\Ruta || $model instanceof \App\Models\User) {
            $builder->where($model instanceof \App\Models\Bus ? 'buses.cooperativa_id' : ($model instanceof \App\Models\Ruta ? 'rutas.cooperativa_id' : 'users.cooperativa_id'), $user->cooperativa_id);
            return;
        }

        if ($model instanceof \App\Models\Frecuencia) {
            $builder->whereHas('ruta', function (Builder $query) use ($user): void {
                $query->where('cooperativa_id', $user->cooperativa_id);
            });
            return;
        }

        if ($model instanceof \App\Models\HojaRuta) {
            $builder->whereHas('frecuencia.ruta', function (Builder $query) use ($user): void {
                $query->where('cooperativa_id', $user->cooperativa_id);
            });
        }
    }
}