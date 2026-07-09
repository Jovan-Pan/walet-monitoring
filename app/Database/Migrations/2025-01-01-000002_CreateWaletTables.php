<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWaletTables extends Migration
{
    public function up()
    {
        // ===========================================================
        // RUMAH WALET
        // ===========================================================
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode'              => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama'              => ['type' => 'VARCHAR', 'constraint' => 100],
            'lokasi'            => ['type' => 'TEXT', 'null' => true],
            'latitude'          => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'longitude'         => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'luas'              => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'jumlah_lantai'     => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'tahun_dibangun'    => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'kapasitas_panen_kg'=> ['type' => 'DECIMAL', 'constraint' => '8,2', 'null' => true],
            'kondisi'           => ['type' => 'ENUM', 'constraint' => ['baik', 'sedang', 'buruk'], 'default' => 'baik'],
            'tanggal_berdiri'   => ['type' => 'DATE', 'null' => true],
            'keterangan'        => ['type' => 'TEXT', 'null' => true],
            'status'            => ['type' => 'ENUM', 'constraint' => ['aktif', 'nonaktif'], 'default' => 'aktif'],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode');
        $this->forge->createTable('rumah_walet');

        // ===========================================================
        // PETUGAS
        // ===========================================================
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nip'            => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'jenis_kelamin'  => ['type' => 'ENUM', 'constraint' => ['L', 'P'], 'default' => 'L'],
            'tempat_lahir'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_lahir'  => ['type' => 'DATE', 'null' => true],
            'alamat'         => ['type' => 'TEXT', 'null' => true],
            'no_hp'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_masuk'  => ['type' => 'DATE', 'null' => true],
            'user_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'status'         => ['type' => 'ENUM', 'constraint' => ['aktif', 'nonaktif'], 'default' => 'aktif'],
            'foto'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nip');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL');
        $this->forge->createTable('petugas');

        // ===========================================================
        // PETUGAS_RUMAH
        // ===========================================================
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'petugas_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'rumah_walet_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal_mulai'   => ['type' => 'DATE'],
            'tanggal_selesai' => ['type' => 'DATE', 'null' => true],
            'catatan'         => ['type' => 'TEXT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('petugas_id', 'petugas', 'id', 'CASCADE');
        $this->forge->addForeignKey('rumah_walet_id', 'rumah_walet', 'id', 'CASCADE');
        $this->forge->createTable('petugas_rumah');

        // ===========================================================
        // INSPEKSI
        // ===========================================================
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'rumah_walet_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'petugas_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal_inspeksi'=> ['type' => 'DATE'],
            'kondisi_bangunan'=> ['type' => 'ENUM', 'constraint' => ['baik', 'sedang', 'buruk']],
            'kondisi_sarang'  => ['type' => 'ENUM', 'constraint' => ['baik', 'sedang', 'buruk']],
            'populasi_walet'  => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'suhu'            => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'kelembaban'      => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'kebersihan'      => ['type' => 'ENUM', 'constraint' => ['baik', 'sedang', 'buruk'], 'default' => 'sedang'],
            'hama'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'catatan'         => ['type' => 'TEXT', 'null' => true],
            'status'          => ['type' => 'ENUM', 'constraint' => ['baik', 'sedang', 'buruk'], 'default' => 'baik'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('rumah_walet_id', 'rumah_walet', 'id', 'CASCADE');
        $this->forge->addForeignKey('petugas_id', 'petugas', 'id', 'CASCADE');
        $this->forge->createTable('inspeksi');

        // ===========================================================
        // JADWAL_PANEN
        // ===========================================================
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'rumah_walet_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'petugas_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'tanggal_rencana'   => ['type' => 'DATE'],
            'periode'           => ['type' => 'VARCHAR', 'constraint' => 20],
            'estimasi_hasil_kg' => ['type' => 'DECIMAL', 'constraint' => '8,2', 'default' => 0.00],
            'status'            => ['type' => 'ENUM', 'constraint' => ['terjadwal', 'selesai', 'ditunda', 'batal'], 'default' => 'terjadwal'],
            'catatan'           => ['type' => 'TEXT', 'null' => true],
            'tanggal_aktual'    => ['type' => 'DATE', 'null' => true],
            'created_by'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('rumah_walet_id', 'rumah_walet', 'id', 'CASCADE');
        $this->forge->addForeignKey('petugas_id', 'petugas', 'id', 'SET NULL');
        $this->forge->createTable('jadwal_panen');

        // ===========================================================
        // HASIL_PANEN
        // ===========================================================
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'jadwal_panen_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'rumah_walet_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'petugas_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal_panen'   => ['type' => 'DATE'],
            'periode'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'grade'           => ['type' => 'ENUM', 'constraint' => ['A', 'B', 'C']],
            'berat_kg'        => ['type' => 'DECIMAL', 'constraint' => '8,3', 'default' => 0.000],
            'harga_per_kg'    => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'kualitas'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'catatan'         => ['type' => 'TEXT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('jadwal_panen_id', 'jadwal_panen', 'id', 'SET NULL');
        $this->forge->addForeignKey('rumah_walet_id', 'rumah_walet', 'id', 'CASCADE');
        $this->forge->addForeignKey('petugas_id', 'petugas', 'id', 'CASCADE');
        $this->forge->createTable('hasil_panen');

        // ===========================================================
        // PENGELUARAN
        // ===========================================================
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tanggal'         => ['type' => 'DATE'],
            'rumah_walet_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'kategori'        => ['type' => 'ENUM', 'constraint' => ['maintenance', 'gaji', 'listrik', 'peralatan', 'pakan', 'lainnya'], 'default' => 'lainnya'],
            'keterangan'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'jumlah'          => ['type' => 'DECIMAL', 'constraint' => '14,2', 'default' => 0.00],
            'bukti'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'input_by'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('rumah_walet_id', 'rumah_walet', 'id', 'SET NULL');
        $this->forge->addForeignKey('input_by', 'users', 'id', 'SET NULL');
        $this->forge->createTable('pengeluaran');
    }

    public function down()
    {
        $this->forge->dropTable('pengeluaran');
        $this->forge->dropTable('hasil_panen');
        $this->forge->dropTable('jadwal_panen');
        $this->forge->dropTable('inspeksi');
        $this->forge->dropTable('petugas_rumah');
        $this->forge->dropTable('petugas');
        $this->forge->dropTable('rumah_walet');
    }
}
