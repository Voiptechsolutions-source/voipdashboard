<?php
namespace App\Http\Controllers\Roles;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        Permission::create([
            'page_name' => $request->page_name,
            'can_view' => $request->has('can_view'),
            'can_edit' => $request->has('can_edit'),
            'can_delete' => $request->has('can_delete'),
        ]);
        return redirect()->back();
    }
}
?>