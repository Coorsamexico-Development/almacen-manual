<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\rol;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        request()->validate([
            'direction' => 'in:asc,desc'
        ]);


        $users = User::select('users.*', 'roles.name as role')
            ->join('roles', 'users.role_id', '=', 'roles.id');

        $roles = rol::select('roles.id', 'roles.name')->get();


        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $users->where('users.nombre', 'like', '%' . $search . '%')
                ->orWhere('users.ap_paterno', 'like', '%' . $search . '%')
                ->orWhere('users.ap_materno', 'like', '%' . $search . '%')
                ->orWhere('users.name', 'like', '%' . $search . '%')
                ->orWhere('roles.name', 'like', '%' . $search . '%');
        }

        if (request()->has('field')) {
            $users->orderBy(request('field'), request('direction'));
        } else {
            $users->orderBy('users.created_at', 'desc');
        }

        return Inertia::render('Users/UsersIndex', [
            'laravelUsers' => $users->paginate(10),
            'laravelRoles' => $roles,
            'filters' => request(['search', 'field', 'direction'])
        ]);
    }





    public function store(UserRequest $request)
    {
        $newUser = $request->validated();
        $newUser['password'] = Hash::make($request->password);
        User::create($newUser);
        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $newUser = $request->validated();
        if (isset($request->password)) {
            $newUser['password'] = Hash::make($request->password);
        }

        $user->update($newUser);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        request()->validate([
            'active' => ['required', 'boolean']
        ]);
        DB::beginTransaction();
        try {
            $user->active = request('active');
            if (!$user->active) {
                DB::delete('delete from sessions where user_id = ?', [$user->id]);
            }
            $user->save();
            DB::commit();
            return response()->json([
                'message' => 'ok'
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json(
                ['message' => $ex->getMessage()],
                500
            );
        }
    }
}
