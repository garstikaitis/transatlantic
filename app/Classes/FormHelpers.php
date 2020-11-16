<?php

namespace App\Classes;

use Exception;
use Illuminate\Validation\Validator;

class FormHelpers
{
    public static function validationMessages()
    {
        return [
            'in'      => 'The :attribute must be one of the following types: :values',
            'integer' => 'The :attribute must be an integer',
            'string' => 'The :attribute must be form of text',
            'required' => 'The :attribute field is required',
            'date_format' => 'The :attribute field must be a date_format "Y-m-d H:i:s" string',
            'nullable' => 'The :attribute field is required but is okay to be null',
            'array' => 'The :attribute field must be an array',
            'boolean' => 'The :attribute field must be false or true',
            'exists' => 'The :attribute must exist in the database',
        ];
    }

    public static function reportFormErrors(Validator $validator)
    {
        if ($validator->fails() == false) {
            return;
        }

        if (! $validator->errors()->any()) {
            abort(403, 'Validation failed without any details');
        }

        $errorCount = count($validator->errors()->all());

        $message = 'Validation failed with '.$errorCount.' errors:';

        $counter = 1;
        foreach ($validator->errors()->all() as $errorMessage) {
            if ($errorCount > 1) {
                $message .= $counter.') ';
            }
            $message .= ' '.$errorMessage;
            if ($counter < $errorCount) {
                $message .= ', ';
            }
            $counter++;
        }
        abort(403, $message);
    }

    public static function exceptionErrorMessage(Exception $e, $long = false)
    {
        $base = 'file: '.$e->getFile().', line: '.$e->getLine().', message: '.$e->getMessage();

        if ($long) {
            return $base.' trace:'.$e->getTraceAsString();
        } else {
            return $base;
        }
    }
}