<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use App\Models\AuditLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen('eloquent.created: *', fn (string $event, array $payload) => $this->writeAudit($payload[0], 'created', null, $payload[0]->getAttributes()));
        Event::listen('eloquent.updated: *', fn (string $event, array $payload) => $this->writeAudit($payload[0], 'updated', $payload[0]->getOriginal(), $payload[0]->getAttributes()));
        Event::listen('eloquent.deleted: *', fn (string $event, array $payload) => $this->writeAudit($payload[0], 'deleted', $payload[0]->getOriginal(), null));
    }

    private function writeAudit(Model $model, string $action, ?array $old, ?array $new): void
    {
        if ($model instanceof AuditLog || !config('app.audit_enabled', true)) return;
        AuditLog::create([
            'school_id' => $model->getAttribute('school_id'),
            'actor_user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $model::class,
            'entity_id' => is_numeric($model->getKey()) ? $model->getKey() : null,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => app()->runningInConsole() ? null : request()->ip(),
            'user_agent' => app()->runningInConsole() ? null : request()->userAgent(),
        ]);
    }
}
