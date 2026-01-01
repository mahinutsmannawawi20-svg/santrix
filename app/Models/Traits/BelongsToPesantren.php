<?php

namespace App\Models\Traits;

use App\Models\Pesantren;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToPesantren
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('pesantren', function (Builder $builder) {
            if (app()->has('CurrentTenant')) {
                $builder->where('pesantren_id', app('CurrentTenant')->id);
            }
        });

        static::creating(function ($model) {
            if (app()->has('CurrentTenant')) {
                $model->pesantren_id = app('CurrentTenant')->id;
            }
        });
    }

    /**
     * Get the pesantren that owns the model.
     */
    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
}
