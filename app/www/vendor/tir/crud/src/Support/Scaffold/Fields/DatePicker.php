<?php

namespace Tir\Crud\Support\Scaffold\Fields;


use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Tir\Crud\Support\Enums\FilterType;

class DatePicker extends BaseField
{
    protected string $type = 'DatePicker';
    protected FilterType|string $filterType = FilterType::DatePicker;

    protected array $timezone = [false, 'UTC'];
    private string $format = 'Y-m-d';

    public function format(string $stringType): DatePicker
    {
        $this->format = $stringType;
        $this->options['dateFormat'] = $this->convertPHPToMomentFormat($stringType);
        return $this;
    }

    public function showTime(string $format = 'H:i:s'): DatePicker
    {
        $this->options['showTime'] = $this->convertPHPToMomentFormat($format);
        return $this;
    }


    public function timezone($status = true, $timezone = 'UTC'): DatePicker
    {
        $this->timezone = [$status, $timezone];
        return $this;
    }

    public function picker(string $type): DatePicker
    {
        $this->options['picker'] = $type;
        return $this;
    }

    protected function setValue($model): void
    {
        if ($model) {
            $date = Arr::get($model, $this->name);
            if (isset($date)) {
                if (gettype($date) != 'object') {
                    $date = Carbon::make($date);
                }
                $this->value = $date;
            }
        }


    }

    private function convertPHPToMomentFormat($format)
    {
        $replacements = [
            'd' => 'DD',
            'D' => 'ddd',
            'j' => 'D',
            'l' => 'dddd',
            'N' => 'E',
            'S' => 'o',
            'w' => 'e',
            'z' => 'DDD',
            'W' => 'W',
            'F' => 'MMMM',
            'm' => 'MM',
            'M' => 'MMM',
            'n' => 'M',
            't' => '', // no equivalent
            'L' => '', // no equivalent
            'o' => 'YYYY',
            'Y' => 'YYYY',
            'y' => 'YY',
            'a' => 'a',
            'A' => 'A',
            'B' => '', // no equivalent
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            's' => 'ss',
            'u' => 'SSS',
            'e' => 'zz', // deprecated since version 1.6.0 of moment.js
            'I' => '', // no equivalent
            'O' => '', // no equivalent
            'P' => '', // no equivalent
            'T' => '', // no equivalent
            'Z' => '', // no equivalent
            'c' => '', // no equivalent
            'r' => '', // no equivalent
            'U' => 'X',
        ];
        $momentFormat = strtr($format, $replacements);
        return $momentFormat;
    }


}
