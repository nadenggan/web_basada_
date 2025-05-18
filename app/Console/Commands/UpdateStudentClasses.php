<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Kelas;

class UpdateStudentClasses extends Command
{
    protected $signature = 'app:update-student-classes'; //command name
    protected $description = 'Memperbarui tingkat kelas siswa dan menandai siswa lulus.'; //command desc

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses kenaikan kelas...'); //message terminal

        User::where('status_siswa', 'Aktif')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $kelasSaatIni = Kelas::where('id_kelas', $user->id_kelas)->first();

                if ($kelasSaatIni) {
                    $tingkatSaatIni = $kelasSaatIni->tingkat_kelas;
                    $jurusanSaatIni = $kelasSaatIni->jurusan;

                    $tingkatBaru = null;

                    if ($tingkatSaatIni === 'X') {
                        $tingkatBaru = 'XI';
                    } elseif ($tingkatSaatIni === 'XI') {
                        $tingkatBaru = 'XII';
                    } elseif ($tingkatSaatIni === 'XII') {
                        $user->update(['status_siswa' => 'Lulus', 'id_kelas' => null]);
                        $this->info("Siswa {$user->name} (NIS: {$user->nis}) telah lulus.");
                        continue;
                    }

                    if ($tingkatBaru) {
                        $kelasBaru = Kelas::where('tingkat_kelas', $tingkatBaru)
                            ->where('jurusan', $jurusanSaatIni)
                            ->first();

                        if ($kelasBaru) {
                            $user->update(['id_kelas' => $kelasBaru->id_kelas]);
                            $this->info("Siswa {$user->name} (NIS: {$user->nis}) naik ke kelas {$tingkatBaru}-{$jurusanSaatIni}.");
                        } else {
                            $this->error("Kelas {$tingkatBaru}-{$jurusanSaatIni} tidak ditemukan untuk siswa {$user->name} (NIS: {$user->nis}).");
                        }
                    }
                } else {
                    $this->warn("Siswa {$user->name} (NIS: {$user->nis}) tidak memiliki ID Kelas yang valid.");
                }
            }
        });

        $this->info('Proses kenaikan kelas selesai.');
    }
}
