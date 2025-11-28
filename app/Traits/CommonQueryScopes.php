<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CommonQueryScopes
{
    public function scopeFilterByDate(Builder $query, $date)
    {
        if ($date) {
            return $query->whereDate('created_at', $date);
        }
        return $query;
    }

    public function scopeSearchByTitle(Builder $query, $title)
    {
        if ($title) {
            return $query->where('title', 'like', "%{$title}%");
        }
        return $query;
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }
}