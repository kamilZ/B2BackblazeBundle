<?php

namespace KamilZ\B2BackblazeBundle\Tests;

use B2Backblaze\B2Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class B2ClientTest extends WebTestCase
{
    public function testServiceIndex()
    {
        $client = static::createClient();
        $service = $client->getContainer()->get('backblaze.b2');
        $this->assertFalse(is_null($service));
    }

    public function testAPIIndex()
    {
        $client = static::createClient();
        $configuration = $client->getContainer()->getParameter('b2_backblaze');

        $accountId = $configuration['account_id'];
        $accountKey = $configuration['account_key'];
        $bucketId = $configuration['bucket_id'];

        $client = new B2Client($accountId, $accountKey, $configuration['timeout']);
        $data = $client->b2AuthorizeAccount();
        $this->assertTrue(array_key_exists('apiUrl', $data));
        $this->assertTrue(array_key_exists('authorizationToken', $data));
        $this->assertTrue(array_key_exists('downloadUrl', $data));
        $this->assertTrue(array_key_exists('accountId', $data));
        $this->assertTrue($data['accountId'] == $accountId);

        $uploadData = $client->b2GetUploadURL($data['apiUrl'], $data['authorizationToken'], $bucketId);
        $this->assertTrue(array_key_exists('bucketId', $uploadData));
        $this->assertTrue(array_key_exists('uploadUrl', $uploadData));
        $this->assertTrue(array_key_exists('authorizationToken', $uploadData));
        $this->assertTrue($uploadData['bucketId'] == $bucketId);

        $fileName = 'apple.jpg';
        $file = __DIR__.'/'.$fileName;
        $fileUploaded = $client->b2UploadFile(
            $file,
            $uploadData['uploadUrl'],
            $uploadData['authorizationToken'],
            $fileName
        );
        $this->assertFalse(array_key_exists('code', $fileUploaded));
        $this->assertTrue(array_key_exists('accountId', $fileUploaded));
        $this->assertTrue(array_key_exists('bucketId', $fileUploaded));
        $this->assertTrue(array_key_exists('contentLength', $fileUploaded));
        $this->assertTrue(array_key_exists('contentSha1', $fileUploaded));
        $this->assertTrue(array_key_exists('contentType', $fileUploaded));
        $this->assertTrue(array_key_exists('fileId', $fileUploaded));
        $this->assertTrue(array_key_exists('fileInfo', $fileUploaded));
        $this->assertTrue(array_key_exists('fileName', $fileUploaded));
        $this->assertTrue($fileUploaded['fileName'] == $fileName);
        $this->assertTrue($fileUploaded['bucketId'] == $bucketId);
        $this->assertTrue($fileUploaded['accountId'] == $accountId);
    }
}
