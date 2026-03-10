<?php

namespace App\Concerns;

use App\Models\AuditLog;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::updated(function ($model) {
            static::logChange($model, 'updated');
        });

        static::deleted(function ($model) {
            static::logChange($model, 'deleted');
        });
    }

    protected static function logChange($model, $action)
    {
        $dirty = $model->getDirty();
        $old = array_intersect_key($model->getOriginal(), $dirty);

        AuditLog::create([
            'user_id' => auth()->id(),
            'tenant_id' => session('tenant_id'),
            'action' => $action,
            'table_name' => $model->getTable(),
            'record_id' => $model->id,
            'old_value' => json_encode($old),
            'new_value' => json_encode($dirty),
            'ip_address' => request()->ip(),
        ]);
    }
}
