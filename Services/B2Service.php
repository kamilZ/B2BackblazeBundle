<?php

namespace KamilZ\B2BackblazeBundle\Services;

/**
 * Backblaze B2 cloud storage helper interface.
 */
class B2Service
{
    /**
     * @var \B2Backblaze\B2Service Client
     */
    protected $b2;
    protected $bucket;
    protected $region;
    protected $apiURL;
    /**
     * Last valid token
     * @var String
     */
    protected $token;

    /**
     * @param array $config Symfony2 service configuration as parameter.
     */
    public function __construct(array $config)
    {
        $this->b2 = new \B2Backblaze\B2Service(
            $config['account_id'],
            $config['account_key'],
            $config['timeout']
        );
        $this->bucket = $config['bucket_id'];
        $this->region = $config['bucket_region'];
    }

    /**
     * Upload file by file path.
     *
     * @param String $path
     * @param String $filename
     *
     * @return bool
     * @throws \B2Backblaze\B2Exception
     */
    public function uploadFile($path, $filename)
    {
        try{
            $data =  $this->b2->insert($this->bucket,file_get_contents($path),$filename);
            if(!is_array($data)) return false;
        }catch (\B2Backblaze\B2Exception $e){
            return false;
        }
        return true;
    }
    /**
     * Upload file by file content.
     *
     * @param mixed $file
     * @param String $filename
     *
     * @return bool
     * @throws \B2Backblaze\B2Exception
     */
    public function uploadFileByContent($file, $filename)
    {
        try{
            $data =  $this->b2->insert($this->bucket,$file,$filename);
            if(!is_array($data)) return false;
        }catch (\B2Backblaze\B2Exception $e){
            return false;
        }
        return true;
    }

}
