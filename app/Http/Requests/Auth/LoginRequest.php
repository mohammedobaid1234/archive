<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            // 'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        
        $employee = \Modules\Employees\Entities\Employee::where('employment_id', trim(request()->employment_id))->select('id')->first();
        
        if($employee){
            $account = $employee;
            $userable_type = "Modules\Employees\Entities\Employee";
        }else{
            throw ValidationException::withMessages([
                'الرقم الوظيفي' => 'يرجى التحقق من الرقم المدخل.'
            ]);
        }

        if(!$account->user){
            throw ValidationException::withMessages([
                'حالة الحساب' => 'الملف المدخل لا يملك حساب على النظام، يرجى مراجعة الإدارة.'
            ]);
        }

        if(!Auth::attempt([
            "userable_id" => $account->id,
            "userable_type" => $userable_type,
            "password" => request()->password,
        ], $this->filled('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                "خطأ في البيانات، الرجاء ادخال البيانات صحيحة"
            ]);
        }


        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('employment_id')).'|'.$this->ip();
    }
}
