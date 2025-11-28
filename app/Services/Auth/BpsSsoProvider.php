<?php

namespace App\Services\Auth;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Facades\Http;

class BpsSsoProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Helper untuk mendapatkan URL Base Keycloak yang benar
     */
    protected function getKeycloakBaseUrl()
    {
        $baseUrl = config('services.bps.base_url');
        $realm = config('services.bps.realm');
        // Format URL Keycloak standar: /auth/realms/{realm}/protocol/openid-connect
        return "{$baseUrl}/auth/realms/{$realm}/protocol/openid-connect";
    }

    /**
     * URL Otorisasi (Halaman Login BPS)
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getKeycloakBaseUrl() . '/auth', $state);
    }

    /**
     * URL Token (Menukar kode dengan token akses)
     */
    protected function getTokenUrl()
    {
        return $this->getKeycloakBaseUrl() . '/token';
    }

    /**
     * Mengambil Data User (UserInfo + API Pegawai)
     */
    protected function getUserByToken($token)
    {
        // 1. Panggil UserInfo standar OIDC untuk dapat username/email dasar
        $userInfoUrl = $this->getKeycloakBaseUrl() . '/userinfo';
        
        $response = $this->getHttpClient()->get($userInfoUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        $user = json_decode($response->getBody(), true);

        // 2. INTEGRASI API PEGAWAI (Sesuai Panduan BPS)
        // Kita butuh data lengkap (NIP, Jabatan, dll) yang ada di API ini.
        // Endpoint: /auth/realms/{realm}/api-pegawai/username/{username}
        
        if (isset($user['preferred_username'])) {
            $baseUrl = config('services.bps.base_url');
            $realm = config('services.bps.realm');
            $username = $user['preferred_username'];
            
            $apiPegawaiUrl = "{$baseUrl}/auth/realms/{$realm}/api-pegawai/username/{$username}";
            
            try {
                $responseApi = $this->getHttpClient()->get($apiPegawaiUrl, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token, // Token SSO dipakai juga di sini
                        'Content-Type' => 'application/json',
                    ],
                ]);
                
                if ($responseApi->getStatusCode() == 200) {
                    $dataPegawai = json_decode($responseApi->getBody(), true);
                    
                    // API BPS mengembalikan array, ambil item pertama
                    if (is_array($dataPegawai) && count($dataPegawai) > 0) {
                        $pegawai = $dataPegawai[0];
                        
                        // Gabungkan attributes (NIP, Jabatan, dll) ke data user utama
                        if (isset($pegawai['attributes'])) {
                            foreach ($pegawai['attributes'] as $key => $val) {
                                // Atribut Keycloak seringkali berupa array, ambil index 0
                                $user[$key] = is_array($val) ? $val[0] : $val;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Jika API Pegawai gagal/timeout, biarkan (pakai data dasar saja)
            }
        }

        return $user;
    }

    /**
     * Mapping Data JSON ke Objek User Laravel Socialite
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'       => $user['sub'] ?? null,
            'name'     => $user['name'] ?? $user['nama'] ?? null,
            'email'    => $user['email'] ?? null,
            'username' => $user['preferred_username'] ?? null,
            
            // Data spesifik dari API Pegawai (Mapping sesuai field BPS)
            'nip'             => $user['nip_baru'] ?? $user['nip'] ?? null, 
            'kode_organisasi' => $user['kode_organisasi'] ?? null,
            'jabatan'         => $user['jabatan'] ?? null,
            'url_foto'        => $user['url_foto'] ?? null,
        ]);
    }
}