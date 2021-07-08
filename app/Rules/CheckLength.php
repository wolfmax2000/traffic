<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckLength implements Rule
{
    public $title;
    public $desc;

    public function __construct($title, $desc)
    {
        $this->title = $title;
        $this->desc = $desc;
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
        return mb_strlen($this->title . $this->desc) < 100;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Максимальная длина заголовка + описания должна быть не более 100';
    }
}