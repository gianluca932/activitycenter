<?php

namespace App\Imports;

use App\Models\Volunteer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VolunteersImport implements ToCollection, WithHeadingRow
{
    protected $baseId;

    public int $importedCount = 0;
    public int $skippedCount = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function __construct($baseId)
    {
        $this->baseId = $baseId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $excelRow = $index + 2; // heading row + data rows

            $fullname = trim((string) ($row['nome_completo'] ?? $row['fullname'] ?? ''));
            $taxCode = $this->nullableString($row['codice_fiscale'] ?? $row['tax_code'] ?? null);

            if ($fullname === '') {
                $this->skippedCount++;
                $this->errors[] = "Riga {$excelRow}: manca il nome completo.";
                continue;
            }

            if ($taxCode === null) {
                $this->skippedCount++;
                $this->errors[] = "Riga {$excelRow}: manca il codice fiscale.";
                continue;
            }

            $alreadyExists = Volunteer::where('tax_code', $taxCode)->exists();

            if ($alreadyExists) {
                $this->skippedCount++;
                $this->errors[] = "Riga {$excelRow}: Volontario con codice fiscale {$taxCode} già esistente.";
                continue;
            }

            $volunteer = new Volunteer([
                'fullname' => $fullname,
                'luogo_di_nascita' => $this->nullableString($row['luogo_di_nascita'] ?? null),
                'numero_iscrizione_regionale' => $this->nullableString($row['numero_iscrizione_regionale'] ?? $row['n_iscrizione_regionale'] ?? null),
                'residenza' => $this->nullableString($row['residenza'] ?? null),
                'cellulare' => $this->nullableString($row['cellulare'] ?? $row['telefono'] ?? null),
                'email' => $this->nullableString($row['email'] ?? null),
                'patenti' => $this->nullableString($row['patenti'] ?? null),
                'tax_code' => $taxCode,
                'base_id' => $this->baseId,
            ]);

            $volunteer->saveQuietly();
            $this->importedCount++;
        }
    }

    private function nullableString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}