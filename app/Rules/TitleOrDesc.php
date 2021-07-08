<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TitleOrDesc implements Rule
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
        
        return !empty($this->title) || !empty($this->desc);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Необходимо заполнить заголовок или описание';
    }
}