<?php

namespace KamilZ\B2BackblazeBundle\Services;

/**
 * B2Service helper interface.
 */
class B2Service
{
    /**
     * @var \B2Backblaze\B2Client Client
     */
    protected $b2;
    protected $bucket;
    protected $region;
    protected $apiURL;
    /**
     * @var String Last valid token.
     */
    protected $token;

    /**
     * @param array $config Symfony2 configuration parameters.
     */
    public function __construct(array $config)
    {
        $this->b2 = new \B2Backblaze\B2Client(
            $config['account_id'],
            $config['account_key'],
            $config['timeout']
        );
        $this->bucket = $config['bucket_id'];
        $this->region = $config['bucket_region'];
    }

    /**
     * @param String $path     filepath
     * @param String $filename
     *
     * @return bool TRUE if file was uploaded or FALSE if not.
     *
     * @throws \B2Backblaze\B2Exception
     */
    public function uploadFile($path, $filename)
    {
        $data = $this->b2->b2AuthorizeAccount();
        if ($data['code']) {
            return false;
        }
        $this->apiURL = $data['apiUrl'];
        $this->token = $data['authorizationToken'];
        $data = $this->b2->b2GetUploadURL($this->apiURL, $this->token, $this->bucket);
        if ($data['code']) {
            return false;
        }
        $this->token = $data['authorizationToken'];
        $data = $this->b2->b2UploadFile($path, $data['uploadUrl'], $this->token, $filename);
        if ($data['code']) {
            return false;
        }

        return true;
    }
}
