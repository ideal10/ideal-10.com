<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'email', 'password']);
        $data['is_admin'] = $request->boolean('is_admin');

        User::create($data);

        return redirect()->route('admin.users.index')->with('status', 'Usuario creado.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if ($user->is($request->user()) && $user->is_admin && ! $request->boolean('is_admin')) {
            return back()->withErrors(['is_admin' => 'No puedes quitarte tu propio permiso de administrador.']);
        }

        $data = $request->safe()->only(['name', 'email']);
        $data['is_admin'] = $request->boolean('is_admin');

        if (filled($request->validated('password'))) {
            $data['password'] = $request->validated('password');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'Usuario actualizado.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 403, 'No puedes eliminar tu propia cuenta.');

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Usuario eliminado.');
    }
}
