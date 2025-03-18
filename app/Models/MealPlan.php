<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealPlan extends Model
{
    use HasUuids;

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
