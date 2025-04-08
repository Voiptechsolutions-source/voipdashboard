<?php
// app/Models/Permission.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['page_name', 'can_view', 'can_edit', 'can_delete'];

    /**
     * A permission belongs to many roles (many-to-many).
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission')
        ->withPivot('can_view', 'can_edit', 'can_delete')
        ->withTimestamps();
    }
}