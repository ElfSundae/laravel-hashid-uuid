<?php

namespace ElfSundae\Laravel\Hashid;

use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class UuidDriver implements DriverInterface
{
    /**
     * The hashid manager instance.
     *
     * @var \ElfSundae\Laravel\Hashid\HashidManager
     */
    protected $manager;

    /**
     * The hashid connection name for encoding and decoding.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create a new UUID driver instance.
     *
     * @param  \ElfSundae\Laravel\Hashid\HashidManager  $manager
     * @param  array  $config
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(HashidManager $manager, array $config)
    {
        $this->manager = $manager;

        if (isset($config['connection'])) {
            $this->connection = $config['connection'];
        } else {
            throw new InvalidArgumentException('A connection name must be specified.');
        }
    }

    /**
     * Encode the data.
     *
     * @param  string|\Ramsey\Uuid\Uuid  $data
     * @return string
     */
    public function encode($data)
    {
        $uuid = $data instanceof Uuid ? $data : Uuid::fromString($data);

        return $this->getConnection()->encode($uuid->getBytes());
    }

    /**
     * Decode the data.
     *
     * @param  string  $data
     * @return \Ramsey\Uuid\Uuid
     */
    public function decode($data)
    {
        try {
            return Uuid::fromBytes($this->getConnection()->decode($data));
        } catch (Exception $e) {
            unset($e);
        }

        return Uuid::fromString(Uuid::NIL);
    }

    /**
     * Get the hashid connection instance.
     *
     * @return mixed
     */
    protected function getConnection()
    {
        return $this->manager->connection($this->connection);
    }
}
