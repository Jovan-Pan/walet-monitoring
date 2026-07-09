<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // 1. Reset users table dengan password hash valid (force change password)
        // Disable FK check dulu karena users di-reference oleh banyak tabel
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('users')->truncate();
        // Truncate harga_grade juga agar seeder idempotent (hindari duplicate key)
        $this->db->table('harga_grade')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        $now = date('Y-m-d H:i:s');

        $users = [
            [
                'nama'                   => 'Administrator',
                'username'               => 'admin',
                'password'               => password_hash('admin123', PASSWORD_BCRYPT),
                'email'                  => 'admin@walet-monitoring.test',
                'role'                   => 'admin',
                'status'                 => 'aktif',
                'must_change_password'   => 1,
                'created_at'             => $now,
                'updated_at'             => $now,
            ],
            [
                'nama'                   => 'Budi Santoso',
                'username'               => 'petugas',
                'password'               => password_hash('petugas123', PASSWORD_BCRYPT),
                'email'                  => 'petugas@walet-monitoring.test',
                'role'                   => 'petugas',
                'status'                 => 'aktif',
                'must_change_password'   => 1,
                'created_at'             => $now,
                'updated_at'             => $now,
            ],
            [
                'nama'                   => 'Harto Wijaya',
                'username'               => 'owner',
                'password'               => password_hash('owner123', PASSWORD_BCRYPT),
                'email'                  => 'owner@walet-monitoring.test',
                'role'                   => 'owner',
                'status'                 => 'aktif',
                'must_change_password'   => 1,
                'created_at'             => $now,
                'updated_at'             => $now,
            ],
        ];

        foreach ($users as $u) {
            $this->db->table('users')->insert($u);
        }

        // 2. Seed harga_grade master untuk periode berjalan & beberapa bulan ke depan
        $currentPeriode = date('Y-m');
        $nextPeriode    = date('Y-m', strtotime('+1 month'));

        $hargaTemplate = [
            ['A', 'urat',        18000000, 25000000, 22000000],
            ['A', 'sarang_utuh', 13000000, 18000000, 15000000],
            ['A', 'kecil',       10000000, 13000000, 12000000],
            ['B', 'urat',        12000000, 15000000, 13000000],
            ['B', 'sarang_utuh',  8000000, 12000000, 10000000],
            ['B', 'kecil',        5000000,  8000000,  6000000],
            ['C', 'urat',         6000000,  8000000,  7000000],
            ['C', 'sarang_utuh',  4000000,  6000000,  5000000],
            ['C', 'kecil',        2500000,  4000000,  3000000],
        ];

        foreach ([$currentPeriode, $nextPeriode] as $periode) {
            foreach ($hargaTemplate as $h) {
                $this->db->table('harga_grade')->insert([
                    'grade'         => $h[0],
                    'jenis_panen'   => $h[1],
                    'periode'       => $periode,
                    'harga_min'     => $h[2],
                    'harga_max'     => $h[3],
                    'harga_default' => $h[4],
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }

        echo "InitialSeeder: 3 users + 18 harga_grade rows inserted.\n";
        echo "Default login: admin/admin123, petugas/petugas123, owner/owner123\n";
        echo "All users MUST change password on first login.\n";
    }
}
