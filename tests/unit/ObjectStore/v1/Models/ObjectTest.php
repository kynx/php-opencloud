<?php declare(strict_types=1);

namespace Rackspace\Test\ObjectStore\v1;

use OpenStack\Test\TestCase;
use Rackspace\ObjectStore\v1\Api;
use Rackspace\ObjectStore\v1\Models\RackspaceObject;

class ObjectTest extends TestCase
{
    private $object;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->object = new RackspaceObject($this->client->reveal(), new Api());
    }

    public function test_extends_openstack()
    {
        $this->assertInstanceOf(\OpenStack\ObjectStore\v1\Models\Object::class, $this->object);
    }
}
