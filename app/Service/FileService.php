<?php

namespace App\Service;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\Permission;

class FileService
{
    /**
     * Create a new class instance.
     */
    protected $client;
    protected $service;
    public function __construct()
    {
        $this->client=new GoogleClient();
        $this->client->setClientId(config('filesystems.disks.google.clientId'));
        $this->client->setClientSecret(config('filesystems.disks.google.clientSecret'));
        $this->client->refreshToken(config('filesystems.disks.google.refreshToken'));
        $this->service=new GoogleDrive($this->client);
        // dd($this->service); 
    }

    public function accessToken(){
        $token=$this->client->getAccessToken();
        if($this->client->isAccessTokenExpired()){
            $token=$this->client->fetchAccessTokenWithRefreshToken(config('filesystems.disks.google.refreshToken'));

        }
        // dd($token);
        return $token['access_token'];
    }

    public function makeFileToPublic($fileid){
        $permission= new Permission([
            'type'=>'anyone',
            'role'=>'reader'
        ]);

        $this->service->permissions->create($fileid,$permission);
    }
}
