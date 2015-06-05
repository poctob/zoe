<?php

return [
    /**
     * This maps column names to their location in the PDF file.
     */
        'columns' => [
            'PROVIDER_REFERENCE' => 0,
            'CLAIM_REFERENCE' => 1,
            'PY_IND' => 2,
            'SERVICE_DATE' => 3,
            'RENDERED_PROC' => 4,
            'AMOUNT_BILLED' => 5,
            'TITLE19_PAYMENT_MA' => 6,
            'STS' => 7,
            'RECIPIENT_ID' => 8,
            'RECIPIENT_NAME' => 9,
            'MOD' => 10,
            'TITLE18_ALLOWED_CHARGES' => 11,
            'COPAY_AMT' => 12,
            'TITLE_18_PAYMENT' => 13,
            'MAX' => 14,
            ],
    /**
     * Delimiters used in the file
     */
        'delimiters' => [
            'BOX_SEPARATOR' => '+',
            'COLUMN_SEPARATOR' => '|',
            'ROW_SEPARATOR' => '-', 
        ],
];
