<?php


namespace App\Http\Controllers;


class FieldController extends Controller
{
    public function get()
    {
        $fields = ['input'];
        return response($fields, 200);
    }

}
