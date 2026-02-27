<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    public static function bootLogsActivity()
    {
        // Log creation
        static::created(function ($model) {
            $model->logActivity('created', $model->toArray());
        });

        // Log updates
        static::updated(function ($model) {
            $changes = $model->getChanges();
            $original = array_intersect_key($model->getOriginal(), $changes);
            
            $model->logActivity('updated', $changes, $original);
        });

        // Log deletions
        static::deleted(function ($model) {
            $model->logActivity('deleted', null, $model->toArray());
        });
    }

    /**
     * Log an activity
     */
    public function logActivity($action, $newData = null, $oldData = null)
    {
        // Skip if no user is logged in (for CLI commands, etc.)
        if (!Auth::check()) {
            return;
        }

        $module = class_basename($this);
        
        // Generate a description
        $description = $this->generateDescription($action, $module);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => strtolower($module),
            'description' => $description,
            'model_type' => get_class($this),
            'model_id' => $this->getKey(),
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'performed_at' => now(),
        ]);
    }

    /**
     * Generate a human-readable description
     */
    protected function generateDescription($action, $module)
    {
        $name = $this->name ?? $this->title ?? "ID: {$this->getKey()}";
        
        $actions = [
            'created' => 'created',
            'updated' => 'updated',
            'deleted' => 'deleted',
            'restored' => 'restored',
        ];

        $actionText = $actions[$action] ?? $action;
        
        return ucfirst("{$module} '{$name}' was {$actionText}");
    }
}