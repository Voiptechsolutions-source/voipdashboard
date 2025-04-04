<?php
// app/Models/Role.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    /**
     * A role has many users.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * A role has many permissions (many-to-many).
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission')
        ->withPivot('can_view', 'can_edit', 'can_delete')
        ->withTimestamps();
    }
}