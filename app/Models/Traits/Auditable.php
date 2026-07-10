<?php

namespace App\Models\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            static::log('created', $model);
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            $original = [];
            foreach ($dirty as $key => $value) {
                $original[$key] = $model->getOriginal($key);
            }
            static::log('updated', $model, [
                'old' => $original,
                'new' => $dirty,
            ]);
        });

        static::deleted(function ($model) {
            static::log('deleted', $model, [
                'data' => $model->getAttributes(),
            ]);
        });
    }

    protected static function log(string $action, $model, array $extra = [])
    {
        $user = Auth::user();
        $label = method_exists($model, 'auditLabel') ? $model->auditLabel() : $model->getKey();

        $description = match ($action) {
            'created' => class_basename($model) . " '{$label}' created",
            'updated' => class_basename($model) . " '{$label}' updated",
            'deleted' => class_basename($model) . " '{$label}' deleted",
            default => class_basename($model) . " '{$label}' {$action}",
        };

        static::write($action, $model, $description, $extra);
    }

    protected static function write(string $action, $model, string $description, array $extra = [])
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'changes' => !empty($extra) ? $extra : null,
            'description' => $description,
        ]);
    }

    public function auditLabel(): string
    {
        return $this->course_title ?? $this->full_name ?? $this->name ?? "#{$this->getKey()}";
    }
}
