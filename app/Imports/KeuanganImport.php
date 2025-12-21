<?php

namespace App\Imports;

use App\Models\Keuangan;
use App\Models\Kategori;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KeuanganImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, WithChunkReading
{
    protected $ponpesId;
    protected $userId;
    protected $successCount = 0;
    protected $failures = [];
    protected $errorMessages = [];

    public function __construct($ponpesId, $userId)
    {
        $this->ponpesId = $ponpesId;
        $this->userId = $userId;
        
        Log::info('Initializing KeuanganImport', [
            'ponpes_id' => $ponpesId,
            'user_id' => $userId
        ]);
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        Log::info('Starting import process', ['total_rows' => $rows->count()]);
        
        foreach ($rows as $index => $row) {
            try {
                $rowNumber = $index + 2; // +2 karena header di row 1 dan index dimulai dari 0
                
                Log::debug('Processing row', [
                    'row_number' => $rowNumber,
                    'row_data' => $row->toArray()
                ]);

                // Parse dan validasi data
                $parsedData = $this->parseAndValidateRow($row, $rowNumber);
                
                if (!$parsedData['valid']) {
                    $this->errorMessages[] = [
                        'row' => $rowNumber,
                        'errors' => $parsedData['errors'],
                        'data' => $row->toArray()
                    ];
                    continue;
                }

                // Simpan ke database
                $keuangan = Keuangan::create([
                    'ponpes_id' => $this->ponpesId,
                    'user_id' => $this->userId,
                    'jumlah' => $parsedData['data']['jumlah'],
                    'id_kategori' => $parsedData['data']['id_kategori'],
                    'sumber_dana' => $parsedData['data']['sumber_dana'],
                    'status' => $parsedData['data']['status'],
                    'tanggal' => $parsedData['data']['tanggal'],
                    'keterangan' => $parsedData['data']['keterangan']
                ]);

                $this->successCount++;
                
                Log::info('Successfully imported row', [
                    'row_number' => $rowNumber,
                    'keuangan_id' => $keuangan->id_keuangan,
                    'jumlah' => $keuangan->jumlah,
                    'status' => $keuangan->status
                ]);

            } catch (\Exception $e) {
                Log::error('Error importing row', [
                    'row_number' => $index + 2,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                $this->errorMessages[] = [
                    'row' => $index + 2,
                    'errors' => [$e->getMessage()],
                    'data' => $row->toArray()
                ];
            }
        }
        
        Log::info('Import completed', [
            'success_count' => $this->successCount,
            'error_count' => count($this->errorMessages),
            'total_rows' => $rows->count()
        ]);
    }

    /**
     * Parse and validate single row
     */
    private function parseAndValidateRow($row, $rowNumber)
    {
        $errors = [];
        $data = [];

        try {
            // 1. Parse Jumlah
            $data['jumlah'] = $this->parseJumlah($row);
            if ($data['jumlah'] === false || $data['jumlah'] <= 0) {
                $errors[] = 'Jumlah harus angka positif';
            }

            // 2. Parse Status
            $data['status'] = $this->parseStatus($row);
            if ($data['status'] === false) {
                $errors[] = 'Status harus "Masuk" atau "Keluar"';
            }

            // 3. Parse Tanggal
            $data['tanggal'] = $this->parseTanggal($row);
            if ($data['tanggal'] === false) {
                $errors[] = 'Format tanggal tidak valid (gunakan YYYY-MM-DD)';
            }

            // 4. Parse Kategori (optional)
            $data['id_kategori'] = $this->parseKategori($row);

            // 5. Parse Sumber Dana (optional)
            $data['sumber_dana'] = $this->parseSumberDana($row);

            // 6. Parse Keterangan (optional)
            $data['keterangan'] = $this->parseKeterangan($row);

        } catch (\Exception $e) {
            $errors[] = 'Error parsing data: ' . $e->getMessage();
        }

        if (empty($errors)) {
            return [
                'valid' => true,
                'data' => $data,
                'errors' => []
            ];
        }

        return [
            'valid' => false,
            'data' => $data,
            'errors' => $errors
        ];
    }

    /**
     * Parse Jumlah dari berbagai format
     */
    private function parseJumlah($row)
    {
        $value = $this->getValue($row, [
            'jumlah', 'Jumlah', 'amount', 'Amount', 
            'nilai', 'Nilai', 'total', 'Total'
        ]);

        if (empty($value)) {
            return false;
        }

        // Jika sudah numeric
        if (is_numeric($value)) {
            return floatval($value);
        }

        // Hapus karakter non-numeric kecuali titik, koma, dan minus
        $value = preg_replace('/[^0-9.,-]/', '', (string) $value);

        // Handle negative numbers
        $isNegative = false;
        if (strpos($value, '-') === 0) {
            $isNegative = true;
            $value = substr($value, 1);
        }

        // Format Indonesia: 1.234,56
        if (strpos($value, '.') !== false && strpos($value, ',') !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }
        // Format dengan koma desimal: 1234,56
        elseif (strpos($value, ',') !== false) {
            $value = str_replace(',', '.', $value);
        }
        // Format dengan titik ribuan: 1.234 -> 1234
        elseif (strpos($value, '.') !== false && strlen($value) - strrpos($value, '.') <= 3) {
            $value = str_replace('.', '', $value);
        }

        $result = floatval($value);
        
        if ($isNegative) {
            $result = -$result;
        }

        return $result;
    }

    /**
     * Parse Status
     */
    private function parseStatus($row)
    {
        $value = $this->getValue($row, [
            'status', 'Status', 'type', 'Type', 
            'tipe', 'Tipe', 'jenis', 'Jenis'
        ]);

        if (empty($value)) {
            return false;
        }

        $value = strtolower(trim($value));

        // Mapping berbagai format status
        $masukAliases = [
            'masuk', 'm', 'pemasukan', 'income', 'in', 
            'debit', 'd', 'penerimaan', 'terima', 't', '+'
        ];
        
        $keluarAliases = [
            'keluar', 'k', 'pengeluaran', 'expense', 'out', 
            'kredit', 'kr', 'pembayaran', 'bayar', 'b', '-'
        ];

        if (in_array($value, $masukAliases)) {
            return 'Masuk';
        }
        
        if (in_array($value, $keluarAliases)) {
            return 'Keluar';
        }

        return false;
    }

    /**
     * Parse Tanggal
     */
    private function parseTanggal($row)
    {
        $value = $this->getValue($row, [
            'tanggal', 'Tanggal', 'date', 'Date', 
            'tgl', 'Tgl', 'waktu', 'Waktu'
        ]);

        if (empty($value)) {
            return false;
        }

        try {
            // Jika numeric (format Excel serial date)
            if (is_numeric($value)) {
                $unixTimestamp = ($value - 25569) * 86400;
                return date('Y-m-d', $unixTimestamp);
            }

            // Coba berbagai format tanggal
            $formats = [
                'Y-m-d',
                'd/m/Y',
                'd-m-Y',
                'm/d/Y',
                'Y/m/d',
                'd.m.Y',
                'Y.m.d',
                'd F Y',
                'd M Y'
            ];

            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $value);
                if ($date && $date->format($format) === $value) {
                    return $date->format('Y-m-d');
                }
            }

            // Coba parse dengan Carbon
            return Carbon::parse($value)->format('Y-m-d');
            
        } catch (\Exception $e) {
            Log::warning('Failed to parse date', [
                'value' => $value,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Parse Kategori
     */
    private function parseKategori($row)
    {
        $namaKategori = $this->getValue($row, [
            'kategori', 'Kategori', 'category', 'Category',
            'jenis', 'Jenis', 'type', 'Type'
        ]);

        if (empty($namaKategori)) {
            return null;
        }

        // Cari kategori berdasarkan nama
        $kategori = Kategori::where('nama_kategori', 'like', '%' . $namaKategori . '%')
            ->where('ponpes_id', $this->ponpesId)
            ->first();

        if ($kategori) {
            return $kategori->id_kategori;
        }

        // Buat kategori baru
        try {
            $newKategori = Kategori::create([
                'ponpes_id' => $this->ponpesId,
                'nama_kategori' => $namaKategori,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info('Created new kategori', [
                'kategori_id' => $newKategori->id_kategori,
                'nama_kategori' => $namaKategori
            ]);

            return $newKategori->id_kategori;
            
        } catch (\Exception $e) {
            Log::error('Failed to create kategori', [
                'nama_kategori' => $namaKategori,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Parse Sumber Dana
     */
    private function parseSumberDana($row)
    {
        $value = $this->getValue($row, [
            'sumber_dana', 'Sumber Dana', 'sumber', 'Sumber',
            'source', 'Source', 'dari', 'Dari'
        ]);

        if (empty($value)) {
            return null;
        }

        return substr($value, 0, 100);
    }

    /**
     * Parse Keterangan
     */
    private function parseKeterangan($row)
    {
        $value = $this->getValue($row, [
            'keterangan', 'Keterangan', 'description', 'Description',
            'catatan', 'Catatan', 'note', 'Note', 'deskripsi', 'Deskripsi'
        ]);

        if (empty($value)) {
            return null;
        }

        return substr($value, 0, 255);
    }

    /**
     * Get value from row with multiple possible keys
     */
    private function getValue($row, $keys)
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && $row[$key] !== null && $row[$key] !== '') {
                return $row[$key];
            }
        }
        return null;
    }

    /**
     * Validation rules untuk WithValidation
     */
    public function rules(): array
    {
        return [
            '*.jumlah' => ['required'],
            '*.status' => ['required'],
            '*.tanggal' => ['required']
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'jumlah.required' => 'Kolom jumlah harus diisi',
            'status.required' => 'Kolom status harus diisi',
            'tanggal.required' => 'Kolom tanggal harus diisi'
        ];
    }

    /**
     * Handle validation failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->failures[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ];
        }
    }

    /**
     * Chunk reading untuk memory optimization
     */
    public function chunkSize(): int
    {
        return 100; // Process 100 rows at a time
    }

    /**
     * Get import results
     */
    public function getResults()
    {
        return [
            'success_count' => $this->successCount,
            'error_count' => count($this->errorMessages),
            'error_messages' => $this->errorMessages,
            'failures' => $this->failures
        ];
    }
}