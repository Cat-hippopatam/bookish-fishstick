<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AdminService
{
    protected $adminFile = 'admin.json';

    public function validateLogin($email, $password)
    {
        if (!Storage::exists($this->adminFile)) {
            $this->createDefaultAdmin();
        }

        $admin = json_decode(Storage::get($this->adminFile), true);
        
        return $admin['email'] === $email && $admin['password'] === $password;
    }

    public function getAdminData()
    {
        if (!Storage::exists($this->adminFile)) {
            $this->createDefaultAdmin();
        }

        return json_decode(Storage::get($this->adminFile), true);
    }

    protected function createDefaultAdmin()
    {
        $defaultAdmin = [
            'email' => 'admin@SweetDreams.ru',
            'password' => 'admin123',
            'name' => 'Администратор'
        ];

        Storage::put($this->adminFile, json_encode($defaultAdmin, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
