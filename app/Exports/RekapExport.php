<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapExport implements WithMultipleSheets
{
    use Exportable;

    protected $pembayarans;
    protected $namaSiswa;

    public function __construct($pembayarans, $namaSiswa)
    {
        $this->pembayarans = $pembayarans;
        $this->namaSiswa = $namaSiswa;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new RekapPembayaranSheet($this->pembayarans, $this->namaSiswa),
        ];

        // Add sheet cicilan
        $hasCicilan = $this->pembayarans->some(function ($pembayaran) {
            return $pembayaran->cicilans->isNotEmpty();
        });

        if ($hasCicilan) {
            $sheets[] = new CicilanSheet($this->pembayarans, $this->namaSiswa);
        }

        return $sheets;
    }

    /**
     * @return string
     */
    public function filename(): string
    {
        return $this->namaSiswa . '_RekapPembayaran_' . date('YmdHis') . '.xlsx';
    }
}

class RekapPembayaranSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle
{
    protected $pembayarans;
    protected $namaSiswa;

    public function __construct($pembayarans, $namaSiswa)
    {
        $this->pembayarans = $pembayarans;
        $this->namaSiswa = $namaSiswa;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->pembayarans;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Jenis Pembayaran',
            'Periode',
            'Bulan',
            'Nominal',
            'Status Pembayaran',
            'Tanggal Lunas',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->jenisPembayaran->nama_jenis_pembayaran,
            $row->jenisPembayaran->periode,
            $row->bulan,
            $row->jenisPembayaran->nominal, 
            $row->status_pembayaran,
            $row->tanggal_lunas ? \Carbon\Carbon::parse($row->tanggal_lunas)->format('d F Y') : '-',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Rekap Pembayaran'; //sheet name
    }
}

class CicilanSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle
{
    protected $pembayarans;
    protected $namaSiswa;

    public function __construct($pembayarans, $namaSiswa)
    {
        $this->pembayarans = $pembayarans;
        $this->namaSiswa = $namaSiswa;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $cicilans = collect();
        foreach ($this->pembayarans as $pembayaran) {
            if ($pembayaran->cicilans->isNotEmpty()) {
                foreach ($pembayaran->cicilans as $cicilan) {
                    $cicilans->push([
                        'nama_jenis_pembayaran' => $pembayaran->jenisPembayaran->nama_jenis_pembayaran,
                        'periode' => $pembayaran->jenisPembayaran->periode,
                        'bulan' => $pembayaran->bulan,
                        'nominal_pembayaran' => $pembayaran->jenisPembayaran->nominal,
                        'status_pembayaran' => $pembayaran->status_pembayaran,
                        'nominal_cicilan' => $cicilan->nominal,
                        'tanggal_bayar_cicilan' => $cicilan->tanggal_bayar ? \Carbon\Carbon::parse($cicilan->tanggal_bayar)->format('d F Y') : '-',
                    ]);
                }
            }
        }
        return $cicilans;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Jenis Pembayaran',
            'Periode',
            'Bulan',
            'Nominal Pembayaran',
            'Status Pembayaran',
            'Nominal Cicilan',
            'Tanggal Bayar Cicilan',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {

        return [
            $row['nama_jenis_pembayaran'],
            $row['periode'],
            $row['bulan'],
            $row['nominal_pembayaran'],
            $row['status_pembayaran'],
            $row['nominal_cicilan'],
            $row['tanggal_bayar_cicilan'],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Detail Cicilan'; //sheet name
    }
}