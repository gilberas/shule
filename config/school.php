<?php

return [

    /*
    |--------------------------------------------------------------------------
    | School Information
    |--------------------------------------------------------------------------
    |
    | Basic school identity used across all PDF documents, receipts,
    | invoices, and report cards.
    |
    */

    'name' => env('SCHOOL_NAME', 'Tanzania School Management System'),
    'short_name' => env('SCHOOL_SHORT_NAME', 'TSMS'),
    'motto' => env('SCHOOL_MOTTO', 'Knowledge for Excellence'),
    'address' => env('SCHOOL_ADDRESS', 'P.O. Box 1234, Dar es Salaam, Tanzania'),
    'phone' => env('SCHOOL_PHONE', '+255 22 123 4567'),
    'email' => env('SCHOOL_EMAIL', 'info@tsms.ac.tz'),
    'website' => env('SCHOOL_WEBSITE', 'www.tsms.ac.tz'),
    'registration_number' => env('SCHOOL_REG_NO', 'SCH-2024-001'),

    /*
    |--------------------------------------------------------------------------
    | Document Settings
    |--------------------------------------------------------------------------
    |
    | Controls for auto-generated receipt numbers, invoice numbers, and
    | admission numbers.
    |
    */

    'receipt_prefix' => env('RECEIPT_PREFIX', 'REC'),
    'invoice_prefix' => env('INVOICE_PREFIX', 'INV'),
    'admission_prefix' => env('ADMISSION_PREFIX', 'TSMS'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */

    'currency' => 'TZS',
    'currency_symbol' => 'TSh',

    /*
    |--------------------------------------------------------------------------
    | PDF Settings
    |--------------------------------------------------------------------------
    */

    'pdf' => [
        'paper_size' => 'A4',
        'orientation' => 'portrait',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 25,
        'margin_bottom' => 25,
    ],

    /*
    |--------------------------------------------------------------------------
    | Academic Settings
    |--------------------------------------------------------------------------
    */

    'pass_marks' => env('PASS_MARKS', 50),
    'total_marks' => env('TOTAL_MARKS', 100),
    'grading_scale' => [
        ['min' => 80, 'max' => 100, 'grade' => 'A', 'remark' => 'Excellent'],
        ['min' => 70, 'max' => 79, 'grade' => 'B', 'remark' => 'Very Good'],
        ['min' => 60, 'max' => 69, 'grade' => 'C', 'remark' => 'Good'],
        ['min' => 50, 'max' => 59, 'grade' => 'D', 'remark' => 'Average'],
        ['min' => 0, 'max' => 49, 'grade' => 'F', 'remark' => 'Fail'],
    ],

];
