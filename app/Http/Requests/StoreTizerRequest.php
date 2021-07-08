<?php

namespace App\Http\Requests;

use App\Rules\CheckLength;
use App\Rules\TitleOrDesc;
use App\Tizer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreTizerRequest extends FormRequest
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
                new TitleOrDesc($this->title, $this->desc),
                new CheckLength($this->title, $this->desc)
            ],
            'price' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'aprove' => [
                'required',
                'numeric'
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
