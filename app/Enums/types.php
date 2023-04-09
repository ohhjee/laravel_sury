<?php

namespace App\Enums;

enum types: string
{
    case type_text = 'text';
    case type_textarea = 'textarea';
    case type_radio = 'radio';
    case type_select = 'select';
    case type_checkbox = 'checkbox';
}
