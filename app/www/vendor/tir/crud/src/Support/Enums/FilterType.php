<?php

namespace Tir\Crud\Support\Enums;

enum FilterType: string
{
    case Slider = 'Slider';
    case Select = 'Select';
    case DatePicker = 'DatePicker';
    case Search = 'Search';
}
