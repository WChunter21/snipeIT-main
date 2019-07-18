<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SaveUserRequest extends FormRequest
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

        $rules = [];

        switch($this->method())
        {

            // Brand new user
            case 'POST':
            {
                \Log::debug('New user');
                $rules['first_name'] = 'required|string|min:1';
                $rules['username'] = 'required_unless:ldap_import,1|string|min:1';
                if ($this->request->get('ldap_import') == false)
                {
                    $rules['password'] = Setting::passwordComplexityRulesSaving('store').'|confirmed';
                }
                break;
            }

            // Save all fields
            case 'PUT':
                $rules['first_name'] = 'required|string|min:1';
                $rules['username'] = 'required_unless:ldap_import,1|string|min:1';
                $rules['password'] = Setting::passwordComplexityRulesSaving('update').'|confirmed';
                break;

            // Save only what's passed
            case 'PATCH':
            {
                $rules['password'] = Setting::passwordComplexityRulesSaving('update');
                break;
            }

            default:break;
        }

        \Log::debug($rules);
        return $rules;

    }

//    /**
//     * Handle a failed validation attempt.
//     *
//     * public function json($data = [], $status = 200, array $headers = [], $options = 0)
//     *
//     * @param  \Illuminate\Contracts\Validation\Validator  $validator
//     * @return void
//     *
//     * @throws \Illuminate\Http\Exceptions\HttpResponseException
//     */
//    protected function failedValidation(Validator $validator)
//    {
//
//        throw new HttpResponseException(response()->json([
//            'message' => 'The given data is invalid',
//            'errors' => $validator->errors()
//        ], 422));
//    }
}
