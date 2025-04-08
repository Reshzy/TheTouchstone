<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255'],
            'is_admin' => 'boolean',
        ];

        // If we're creating a new user, require a password
        if ($this->isMethod('post')) {
            $rules['email'][] = 'unique:users';
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        } 
        // If we're updating, only check email uniqueness excluding the current user
        else if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['email'][] = Rule::unique('users')->ignore($this->user->id);
            
            // Password is optional on update, but if provided, it must meet the requirements
            $rules['password'] = ['nullable', 'confirmed', Password::defaults()];
        }

        return $rules;
    }
}
