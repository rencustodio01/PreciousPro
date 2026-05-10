<?php

namespace App\Observers;

use App\Services\SystemLogService;
use Illuminate\Database\Eloquent\Model;

class SystemLogObserver
{
    protected function buildDescription(string $action, Model $model): string
    {
        $pk = $model->getKeyName();
        $id = $model->{$pk} ?? null;
        return sprintf('%s %s (id=%s)', $action, class_basename($model), $id);
    }

    public function created(Model $model): void
    {
        SystemLogService::log([
            'action' => 'create',
            'model'  => $model->getTable() ?? class_basename($model),
            'meta' => ['attributes' => $model->getAttributes()],
        ]);
    }

    public function updated(Model $model): void
    {
        SystemLogService::log([
            'action' => 'update',
            'model'  => $model->getTable() ?? class_basename($model),
            'meta' => ['changes' => $model->getChanges()],
        ]);
    }

    public function deleted(Model $model): void
    {
        SystemLogService::log([
            'action' => 'delete',
            'model'  => $model->getTable() ?? class_basename($model),
            'meta' => ['attributes' => $model->getAttributes()],
        ]);
    }
}
