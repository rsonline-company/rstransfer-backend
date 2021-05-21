<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|max:50'
        ];
    }

    /**
     * Return error messages in response.
     * 
     * @return array 
     */
    public function messages()
    {
        return [
            'email.required' => 'Adres e-mail jest wymagany.',
            'email.email' => 'Podaj poprawny adres e-mail.',
            'email.max' => 'Podany adres e-mail jest za długi.',
            'email.unique' => 'Podany adres e-mail jest zajęty.',
            'password.required' => 'Hasło jest wymagane.',
            'password.max' => 'Podane hasło jest nieprawidłowe.'
        ];
    }
}
