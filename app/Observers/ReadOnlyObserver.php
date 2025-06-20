<?php

namespace App\Observers;

use App\Exceptions\ReadOnlyException;
use Illuminate\Database\Eloquent\Model;

class ReadOnlyObserver
{
    public function creating(Model $model): void
    {
        throw new ReadOnlyException("The model [{$model->getMorphClass()}] is read-only and cannot be created.");
    }

    public function updating(Model $model): void
    {
        throw new ReadOnlyException("The model [{$model->getMorphClass()}] is read-only and cannot be updated.");
    }

    public function deleting(Model $model): void
    {
        throw new ReadOnlyException("The model [{$model->getMorphClass()}] is read-only and cannot be deleted.");
    }
}
