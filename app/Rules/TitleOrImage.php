<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TitleOrImage implements Rule
{
    public $title;
    public $image;

    public function __construct($title, $image)
    {
        $this->title = $title;
        $this->image = $image;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        
        return (!empty($this->title) && mb_strlen($this->title) <= 100 ) || !empty($this->image);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Необходимо заполнить заголовок (до 100 символов) или картинку';
    }
}