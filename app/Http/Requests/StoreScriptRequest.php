<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreScriptRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('script_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
            ],
            'code' => [
                'required',
            ],
        ];
    }
}
