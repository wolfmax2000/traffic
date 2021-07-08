<?php

namespace App\Http\Requests;

use App\Domain;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreDomainRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('domain_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required',                
            ],
            'template_id' => [
                'required',
            ]
        ];
    }
}
