<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('created');
        });

        static::updated(function ($model) {
            $model->logAudit('updated');
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted');
        });
    }

    protected function logAudit(string $event)
    {
        $old = null;
        $new = null;

        if ($event === 'updated') {
            $old = array_intersect_key($this->getOriginal(), $this->getDirty());
            $new = $this->getDirty();
        } elseif ($event === 'created') {
            $new = $this->getAttributes();
            // Hide sensitive fields
            unset($new['password'], $new['remember_token']);
        } elseif ($event === 'deleted') {
            $old = $this->getOriginal();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'event' => $event,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
