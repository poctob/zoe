<?php

return [
    /**
     * This maps column names to their location in the PDF file.
     */
        'columns' => [
                'PROVIDER_REFERENCE' => [
                    'POSITION' => 0,
                    'TITLE' => 'PROVIDERS OWN REF. NUMBER',
                    'WIDTH' => '9'
                ],
                'CLAIM_REFERENCE' => [
                    'POSITION' => 1,
                    'TITLE' => 'CLAIM REFERENCE NUMBER',
                    'WIDTH' => '17'
                ],
                'PY_IND' => [
                    'POSITION' => 2,
                    'TITLE' => 'PY IND',
                    'WIDTH' => '6'
                ],
                'SERVICE_DATE' => [
                    'POSITION' => 3,
                    'TITLE' => 'SERVICE DATE(S) MMDDYY',
                    'WIDTH' => '9'
                ],
                'RENDERED_PROC' => [
                    'POSITION' => 4,
                    'TITLE' => 'RENDERED PROC.',
                    'WIDTH' => '6'
                ],
                'AMOUNT_BILLED' => [
                    'POSITION' => 5,
                    'TITLE' => 'AMOUNT BILLED',
                    'WIDTH' => '8'
                ],
                'TITLE19_PAYMENT_MA' => [
                    'POSITION' => 6,
                    'TITLE' => 'TITLE 19 PAYMENT MEDICAID',
                    'WIDTH' => '8'
                ],
                'STS' => [
                    'POSITION' => 7,
                    'TITLE' => 'STS',
                    'WIDTH' => '1'
                ],
                'RECIPIENT_ID' => [
                    'POSITION' => 8,
                    'TITLE' => 'RECIPIENT ID. NUMBER',
                    'WIDTH' => '10'
                ],
                'RECIPIENT_NAME' => [
                    'POSITION' => 9,
                    'TITLE' => 'RECIPIENT NAME F M I I LAST NAME',
                    'WIDTH' => '19'
                ],
                'MOD' => [
                    'POSITION' => 10,
                    'TITLE' => 'MOD',
                    'WIDTH' => '3'
                ],
                'TITLE18_ALLOWED_CHARGES' => [
                    'POSITION' => 11,
                    'TITLE' => 'TLE. 18 ALLOWED CHARGES',
                    'WIDTH' => '7'
                ],
                'COPAY_AMT' => [
                    'POSITION' => 12,
                    'TITLE' => 'COPAY AMT',
                    'WIDTH' => '7'
                ],
                'TITLE_18_PAYMENT' => [
                    'POSITION' => 13,
                    'TITLE' => 'TITLE 18 PAYMENT',
                    'WIDTH' => '7'
                ],
                'MAX' => [
                    'POSITION' => 14,
                    'TITLE' => 'NONE',
                    'WIDTH' => '0'
                ],
            ],
    /**
     * Delimiters used in the file
     */
        'delimiters' => [
            'BOX_SEPARATOR' => '+',
            'COLUMN_SEPARATOR' => '|',
            'ROW_SEPARATOR' => '-', 
        ],
    
    /**
     * Application name
     */
        'application' => [
            'NAME' => 'SC Medicaid Converter',
            'TRIAL_TYPE' => 'Standard 14 weeks'
            
        ],
 
];
