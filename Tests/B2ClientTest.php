<?php

namespace KamilZ\B2BackblazeBundle\Tests;

use B2Backblaze\B2API;
use KamilZ\B2BackblazeBundle\Services\B2Service;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class B2ClientTest extends WebTestCase
{
    public function testServiceIndex()
    {
        $client = static::createClient();
        $service = $client->getContainer()->get('backblaze.b2');
        $this->assertFalse(is_null($service));
        $this->assertTrue($service instanceof B2Service);
    }

    public function testAPIIndex()
    {
        $client = static::createClient();
        $configuration = $client->getContainer()->getParameter('b2_backblaze');

        $accountId = $configuration['account_id'];
        $accountKey = $configuration['account_key'];
        $bucketId = $configuration['bucket_id'];

        $client = new B2API($accountId, $accountKey, $configuration['timeout']);
        $response = $client->b2AuthorizeAccount();
        $this->assertTrue($response->isOk());
        $this->assertFalse(is_null($response->get('apiUrl')));
        $this->assertFalse(is_null($response->get('authorizationToken')));
        $this->assertFalse(is_null($response->get('downloadUrl')));
        $this->assertFalse(is_null($response->get('accountId')));
        $this->assertTrue($response->get('accountId') == $accountId);

        $response2 = $client->b2GetUploadURL($response->get('apiUrl'), $response->get('authorizationToken'), $bucketId);
        $this->assertTrue($response2->isOk());
        $this->assertFalse(is_null($response2->get('bucketId')));
        $this->assertFalse(is_null($response2->get('uploadUrl')));
        $this->assertFalse(is_null($response2->get('authorizationToken')));
        $this->assertTrue($response2->get('bucketId') == $bucketId);

        $fileName = 'apple.jpg';
        $file = __DIR__.'/'.$fileName;
        $response3 = $client->b2UploadFile(
            $file,
            $response2->get('uploadUrl'),
            $response2->get('authorizationToken'),
            $fileName
        );
        $this->assertTrue($response3->isOk());
        $this->assertFalse(is_null($response3->get('accountId')));
        $this->assertFalse(is_null($response3->get('bucketId')));
        $this->assertFalse(is_null($response3->get('contentLength')));
        $this->assertFalse(is_null($response3->get('contentSha1')));
        $this->assertFalse(is_null($response3->get('contentType')));
        $this->assertFalse(is_null($response3->get('fileId')));
        $this->assertFalse(is_null($response3->get('fileInfo')));
        $this->assertFalse(is_null($response3->get('fileName')));
        $this->assertTrue($response3->get('fileName') == $fileName);
        $this->assertTrue($response3->get('bucketId') == $bucketId);
        $this->assertTrue($response3->get('accountId') == $accountId);
    }
}
