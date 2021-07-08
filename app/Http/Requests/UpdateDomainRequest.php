<?php

namespace App\Http\Requests;

use App\Domain;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDomainRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('domain_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            ],
            'last_hours'  => [
                'integer',
            ],
            'need_views'  => [
                'integer',
            ],
        ];
    }
}
