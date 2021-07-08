<?php

namespace App\Http\Requests;

use App\News;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreNewsRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('news_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'min:5',
                'max:255',
                'required',
            ],
            'desc'  => [
                'required',
            ],
            'cats' => [
                'required',
            ],
            'country' => [
                'required',
            ], 
        ];
    }
}
