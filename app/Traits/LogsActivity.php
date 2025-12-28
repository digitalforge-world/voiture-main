<?php

namespace App\Traits;

use App\Models\LogActivite;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logAction('Création', $model);
        });

        static::updated(function ($model) {
            static::logAction('Modification', $model);
        });

        static::deleted(function ($model) {
            static::logAction('Suppression', $model);
        });
    }

    protected static function logAction($action, $model)
    {
        // Avoid logging the LogActivite itself if it used the trait (though it shouldn't)
        if ($model instanceof LogActivite) {
            return;
        }

        $details = [];
        if ($action === 'Modification') {
            $details = [
                'old' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                'new' => $model->getDirty(),
            ];
        } elseif ($action === 'Création' || $action === 'Suppression') {
            $details = $model->toArray();
        }

        LogActivite::create([
            'user_id' => Auth::id(),
            'action' => $action . ' de ' . class_basename($model),
            'table_concernee' => $model->getTable(),
            'enregistrement_id' => $model->id,
            'details' => json_encode($details),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
