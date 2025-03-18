<?php

namespace App\Models;

use App\Models\MealPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasUuids;
    
    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class);
    }
}
