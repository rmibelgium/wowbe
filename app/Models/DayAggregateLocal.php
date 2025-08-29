<?php

namespace App\Models;

use App\Observers\ReadOnlyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ReadOnlyObserver::class])]
class DayAggregateLocal extends DayAggregate
{
    protected $table = 'observations_agg_day_local';
}
