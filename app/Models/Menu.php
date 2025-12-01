<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'route_name',
        'icon_svg',
        'parent_id',
        'order',
        'is_active',
        'requires_superuser',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_superuser' => 'boolean',
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Helper methods
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    public function isAccessibleBy(User $user): bool
    {
        // Superuser can access everything
        if ($user->is_superuser) {
            return true;
        }

        // If menu requires superuser, non-superuser cannot access
        if ($this->requires_superuser) {
            return false;
        }

        // Check if user's role has access to this menu
        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();
        $menuRoleIds = $this->roles()->pluck('roles.id')->toArray();

        return !empty(array_intersect($userRoleIds, $menuRoleIds));
    }
}
