<?php

namespace App\Traits;

use App\Models\Auditoria;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::audit('created', $model);
        });

        static::updated(function (Model $model) {
            self::audit('updated', $model);
        });

        static::deleted(function (Model $model) {
            self::audit('deleted', $model);
        });
    }

    protected static function audit(string $action, Model $model)
    {
        $description = match ($action) {
            'created' => "Se creó nuevo registro",
            'updated' => "Se actualizó el registro",
            'deleted' => "Se eliminó el registro",
        };

        Auditoria::create([
            'user_id' => auth()->id() ?? 1,
            'action' => $action,
            'model_type' => class_basename($model),
            'model_id' => $model->id,
            'old_values' => $action === 'created' ? null : $model->getOriginal(),
            'new_values' => $action === 'deleted' ? null : $model->getAttributes(),
            'description' => $description
        ]);
    }
}
