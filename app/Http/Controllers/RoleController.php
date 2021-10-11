<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        return Inertia::Render('Role/Index', ['roles' => Role::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Role::class);
        return Inertia::render('Role/Create', ['permissions' => Permission::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        $data = $request->validate([
            'id' => 'integer|exists:roles,id',
            'name' => 'required|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);
        $role = Role::firstOrCreate(['slug' => Str::slug($data['name'])], [
            'name' => $data['name']
        ]);
        // if (isset($data['id'])) {
        //     $role = Role::find($data['id']);
        // } else {
        //     $role = Role::create([
        //         'name' => $data['name'],
        //         'slug' => Str::slug($data['name'])
        //     ]);
        // }
        $role->permissions()->sync($data['permissions']);
        $role->removeCachedPermSlugs();
        $role->cachePermSlugs();
        session()->flash('success', 'Role Updated');
        return redirect(route('roles.index'));
        // dd($data);
    }

    /**0
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        // with(array('permissions' => function ($q) {
        //     $q->select('permission_id as id');
        // }));

        $permissions = Permission::all();
        return Inertia::Render('Role/Create', [
            'role' => $role,
            'added_permissions' => $role->permissions()->allRelatedIds(),
            'permissions' => Permission::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
