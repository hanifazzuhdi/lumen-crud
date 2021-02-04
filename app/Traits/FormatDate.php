<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatDate
{
    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])
            ->format('d, M Y H:i');
    }

    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])
            ->diffForHumans();
    }
}
