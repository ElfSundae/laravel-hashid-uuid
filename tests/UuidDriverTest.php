<?php

namespace ElfSundae\Laravel\Hashid\Test;

use ElfSundae\Laravel\Hashid\Base62Driver;
use ElfSundae\Laravel\Hashid\HashidManager;
use ElfSundae\Laravel\Hashid\UuidDriver;
use InvalidArgumentException;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UuidDriverTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf(UuidDriver::class, $this->getDriver(['connection' => 'foo']));
    }

    public function testThrowsExceptionWhenNoConnectionSpecified()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A connection name must be specified.');
        $this->getDriver();
    }

    public function testEncodedAndDecoded()
    {
        $driver = $this->getDriver(['connection' => 'foo']);
        $uuid = Uuid::uuid4();

        $encoded1 = $driver->encode($uuid);
        $encoded2 = $driver->encode($uuid->toString());
        $encoded3 = $driver->encode($uuid->getHex());
        $this->assertSame($encoded1, $encoded2);
        $this->assertSame($encoded1, $encoded3);

        $decoded = $driver->decode($encoded1);
        $this->assertTrue($uuid->equals($decoded));
    }

    public function testFailedDecoded()
    {
        $driver = $this->getDriver(['connection' => 'foo']);
        $decoded = $driver->decode('bar');
        $this->assertTrue($decoded->equals(Uuid::fromString(Uuid::NIL)));
    }

    protected function getDriver(array $config = [])
    {
        $manager = m::mock(HashidManager::class);

        if (isset($config['connection'])) {
            $manager->shouldReceive('connection')
                ->with($config['connection'])
                ->andReturn(new Base62Driver);
        }

        return new UuidDriver($manager, $config);
    }
}
