<?php

namespace App\Http\Requests;

use App\PushTemplate;
use App\Push;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdatePushRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
            ],            
            'url' => [
                'required',
            ],
            'geo' => [
                'required',
            ],
        ];
    }
}
