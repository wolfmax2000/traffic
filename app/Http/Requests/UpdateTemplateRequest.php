<?php

namespace App\Http\Requests;

use App\Template;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateTemplateRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'         => [
                'required',
            ],
            'stat_days'  => [
                'required',
                'integer'
            ],
            'categories.*' => [
                'integer',
            ],
            'categories'   => [
                'required',
                'array',
            ],
            'tizer_boost_val' => [
                'integer'
            ],
            'news_boost_val' => [
                'integer'
            ],
        ];
    }
}
