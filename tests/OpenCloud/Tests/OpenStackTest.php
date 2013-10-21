<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests;

use OpenCloud\OpenStack;

class OpenStackTest extends \PHPUnit_Framework_TestCase 
{
    
    private $client;
    private $credentials = array('username' => 'foo', 'password' => 'bar', 'tenantName' => 'baz');
    
    public function __construct()
    {
        $this->client = new OpenStack(RACKSPACE_US, $this->credentials);
        $this->client->addSubscriber(new MockTestObserver);
    }
    
    public function test__construct()
    {
        $client = new OpenStack(RACKSPACE_US, $this->credentials);
    }
    
    public function test_Credentials()
    {
        $client = clone $this->client;
        
        $this->assertEquals($this->credentials, $client->getSecret());

        $this->assertEquals(
            json_encode(array('auth' => array('passwordCredentials' => $this->credentials))), 
            $client->getCredentials()
        );
    }
    
    public function test_Auth_Methods()
    {
        $this->client->authenticate();
        
        $this->assertNotNull($this->client->getExpiration());
        $this->assertNotNull($this->client->getToken());
        $this->assertNotNull($this->client->getCatalog());
        $this->assertNotNull($this->client->getTenant());
        /* ??? */
        $this->assertNotNull($this->client->getUrl());
        $this->assertTrue($this->client->hasExpired());
    }
    
    public function test_Logger()
    {
        $this->assertInstanceOf(
            'OpenCloud\Common\Log\LoggerInterface',
            $this->client->getLogger()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CredentialError
     */
    public function test_Credentials_Fail()
    {
        $client = new OpenStack(RACKSPACE_US, array());
        $client->getCredentials();
    }
    
    public function test_Auth_Url()
    {
        $this->assertEquals(RACKSPACE_US . 'tokens', (string) $this->client->getAuthUrl());
        
        $this->client->setAuthUrl(RACKSPACE_UK);
        $this->assertEquals(RACKSPACE_UK . 'tokens', (string) $this->client->getAuthUrl());
    }
    
    public function test_Factory_Methods()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Service', 
            $this->client->computeService('cloudServersOpenStack', 'DFW')
        );
        $this->assertInstanceOf(
            'OpenCloud\ObjectStore\Service', 
            $this->client->objectStoreService('cloudFiles', 'DFW')
        );
        $this->assertInstanceOf(
            'OpenCloud\Volume\Service', 
            $this->client->volumeService('cloudBlockStorage', 'DFW')
        );
    }
    
}