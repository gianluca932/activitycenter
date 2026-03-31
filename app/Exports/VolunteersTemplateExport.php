<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VolunteersTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nome_completo', // o fullname
            'codice_fiscale', // o tax_code
            'luogo_di_nascita',
            'numero_iscrizione_regionale',
            'residenza',
            'cellulare',
            'email',
            'patenti',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Mario Rossi',
                'RSSMRA80A01H501U',
                'Milano',
                '12345',
                'Milano, Via Roma 1',
                '1234567890',
                'mario.rossi@example.com',
                'B, C',
            ],
            [
                'Giovanna Bianchi',
                'BNCGVN85B02F205X',
                'Roma',
                '67890',
                'Roma, Piazza Garibaldi 2',
                '0987654321',
                'giovanna.bianchi@example.com',
                'A, B',
            ],
        ];
    }
}