<?php

namespace App\Models\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            AuditLog::record('created', $model, null, $model->toArray());
        });

        static::updated(function ($model) {
            $changed = [];
            foreach ($model->getDirty() as $key => $value) {
                $changed[$key] = $model->getOriginal($key);
            }
            if (! empty($changed)) {
                AuditLog::record('updated', $model, $changed, $model->getDirty());
            }
        });

        static::deleted(function ($model) {
            AuditLog::record('deleted', $model, $model->toArray(), null);
        });
    }
}
