<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $unique = $this->id != -1 ? ',' . $this->id . ',id' : '';
        return [
            'name' => ['required', 'max:60', 'unique:users,name' . $unique],
            'email' => ['required', 'max:255', 'email', 'unique:users,email' . $unique],
            'nombre' => ['required', 'max:60'],
            'ap_paterno' => ['required', 'max:60'],
            'ap_materno' => ['max:60'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['sometimes', 'required', 'max:12'],
        ];
    }
}
