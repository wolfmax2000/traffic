<?php

namespace App\Http\Requests;

use App\Source;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreSourceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('source_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'         => [
                'required',
            ],
            'utms'         => [
                'required',
            ],
            'scripts.*' => [
                'integer',
            ],
            'scripts'   => [
                'array',
            ],
        ];
    }
}
