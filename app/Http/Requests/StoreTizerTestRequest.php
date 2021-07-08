<?php

namespace App\Http\Requests;

use App\Rules\CheckLength;
use App\Rules\TitleOrImage;
use App\Tizer;
use App\TizerTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreTizerTestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tizer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                new TitleOrImage($this->title, $this->image),
            ],
            'need_views' => [
                'required',
                'numeric'
            ]
        ];
    }
}
