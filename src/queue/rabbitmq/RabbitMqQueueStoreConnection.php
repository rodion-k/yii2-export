<?php
namespace Da\export\queue\rabbitmq;

use common\extensions\queue\AbstractQueueStoreConnection;
use PhpAmqpLib\Connection\AMQPConnection;

class RabbitMqQueueStoreConnection extends AbstractQueueStoreConnection
{
    /**
     * RabbitMqQueueStoreConnection constructor.
     *
     * @param array $configuration
     *
     * @see connect for options
     */
    public function __construct(array $configuration)
    {
        parent::__construct($configuration);
    }

    /**
     * @return RabbitMqQueueStoreConnection
     */
    public function connect()
    {
        $this->disconnect();
        $host = $this->getConfigurationValue('host', '127.0.0.1');
        $port = $this->getConfigurationValue('port', '5672');
        $user = $this->getConfigurationValue('user', 'guest');
        $password = $this->getConfigurationValue('password', 'guest');
        $this->instance = new AMQPConnection(
            $host,
            $port,
            $user,
            $password
        );

        return $this;
    }

    /**
     * @return AMQPConnection
     */
    public function getInstance()
    {
        if ($this->instance === null) {
            $this->connect();
        }

        return $this->instance;
    }
}