<?php

namespace App\Filters;

class OrderFilter extends QueryFilter
{
    protected $sortable = [
        'number',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'scheduledAt' => 'scheduled_at',
        'status',
        'paymentMethod' => 'payment_method',
        'paymentStatus' => 'payment_status',
    ];

    public function include($relationships)
    {
        $allowedRelationships = ['customer', 'driver', 'truck', 'reviews', 'truckCategory', 'tracking'];

        $relationships = explode(',', $relationships);
        $relationships = array_intersect($relationships, $allowedRelationships);

        return $this->builder->with($relationships);
    }

    public function status($value)
    {
        $values = explode(',', $value);
        return $this->builder->whereIn('status', $values);
    }

    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }

    public function number($value)
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('number', 'like', $likeStr);
    }

    public function paymentMethod($value)
    {
        return $this->builder->where('payment_method', $value);
    }

    public function paymentStatus($value)
    {
        return $this->builder->where('payment_status', $value);
    }

    public function scheduledAt($value)
    {
        return $this->builder->where('scheduled_at', $value);
    }

    public function isScheduled($value)
    {
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        if ((bool) $value) {
            return $this->builder->whereNotNull('scheduled_at');
        }

        return $this->builder->whereNull('scheduled_at');
    }
}
