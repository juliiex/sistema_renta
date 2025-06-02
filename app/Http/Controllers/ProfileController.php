<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Mostrar la vista del perfil del usuario autenticado.
     */
    public function show(): View
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Mostrar el formulario de edición del perfil.
     */
    public function edit(): View
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Actualizar la información del perfil del usuario, incluyendo avatar.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuario,correo,' . $user->id,
            'password' => 'nullable|string|min:8',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->nombre = $request->input('name');
        $user->correo = $request->input('email');

        if ($request->filled('password')) {
            $user->contraseña = Hash::make($request->input('password'));
        }

        if ($request->hasFile('avatar')) {
            // Guardar nombre anterior
            $oldAvatar = $user->avatar;

            // Guardar nueva imagen
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/avatars', $filename);
            $user->avatar = $filename;

            // Borrar imagen anterior si existe
            if ($oldAvatar && Storage::disk('public')->exists('avatars/' . $oldAvatar)) {
                Storage::disk('public')->delete('avatars/' . $oldAvatar);
            }
        }

        $user->save();

        return Redirect::route('profile.show')->with('status', 'Perfil actualizado correctamente.');
    }

    /**
     * Eliminar la cuenta del usuario autenticado.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Cuenta eliminada correctamente.');
    }
}
