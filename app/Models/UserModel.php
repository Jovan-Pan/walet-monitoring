<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;  // P2-7

    protected $allowedFields = [
        'nama', 'username', 'password', 'email', 'role',
        'no_hp', 'foto', 'status', 'must_change_password', 'last_login'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected $validationRules = [
        'nama'     => 'required|min_length[3]|max_length[100]',
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email'    => 'permit_empty|valid_email|max_length[100]',
        'role'     => 'required|in_list[admin,petugas,owner]',
        'status'   => 'required|in_list[aktif,nonaktif]',
    ];

    protected function hashPassword(array $data)
    {
        if (! isset($data['data']['password'])) return $data;
        if (empty($data['data']['password'])) {
            unset($data['data']['password']);
            return $data;
        }
        // Skip if already hashed
        if (preg_match('/^\$2[ayb]\$\d{2}\$/', $data['data']['password'])) {
            return $data;
        }
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        return $data;
    }

    public function findByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }
}
