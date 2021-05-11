<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'files' => 'required|array',
            'uploaderKey' => 'required|string',
            'sendEmail' => 'required|string',
            'emailFrom' => 'required_if:sendEmail,==,true|email',
            'emailTo' => 'required_if:sendEmail,==,true|email',
        ];
    }

    public function messages()
    {
        return [
            'emailFrom.required_if' => 'Podaj swój adres email.',
            'emailFrom.email' => 'Podaj poprawny adres email.'
        ];
    }
}
