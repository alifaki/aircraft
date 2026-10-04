<?php

namespace App\Observers;

use App\Models\AuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditTrailObserver
{
    public function created(Model $model)
    {
        $this->logAction($model, 'created');
    }

    public function updated(Model $model)
    {
        $this->logAction($model, 'updated', $model->getOriginal(), $model->getChanges());
    }

    public function deleted(Model $model)
    {
        $this->logAction($model, 'deleted');
    }

    protected function logAction($model, $action, $oldValues = null, $newValues = null)
    {
        AuditTrail::create([
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip() ?: '127.0.0.1',
            'user_agent' => request()->userAgent(),
            'user_id' => Auth::id()
        ]);
    }
}
