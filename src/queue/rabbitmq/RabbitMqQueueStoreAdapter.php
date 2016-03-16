<?php
namespace Da\export\queue\rabbitmq;

use commmon\extension\queue\JobInterface;
use common\extensions\queue\QueueStoreAdapterInterface;
use yii\base\Object;

class RabbitMqQueueStoreAdapter extends Object implements QueueStoreAdapterInterface
{
    /**
     * @var string the queue name
     */
    public $queueName;

    /**
     * @var string
     */
    public $routingKey;
    /**
     * @var RabbitMqQueueStoreConnection
     */
    public $connection;

    /** @var bool */
    public $passive = false;

    /** @var bool */
    public $durable = false;

    /** @var bool */
    public $exclusive = false;

    /** @var bool */
    public $auto_delete = true;

    /** @var bool */
    public $nowait = false;

    /** @var bool */
    public $arguments = null;

    /** @var bool */
    public $ticket = null;

    /**
     * @return BeanstalkdQueueStoreAdapter
     */
    public function init()
    {
        parent::init();

        $this->getConnection()->connect();
    }

    /**
     * @return BeanstalkdQueueStoreConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param string $message
     *
     * @return int
     */
    public function enqueue($message)
    {
        $this->getConnection()->getChannel()->queue_declare(
            $this->queueName,
            $this->passive,
            $this->durable,
            $this->exclusive,
            $this->auto_delete,
            $this->nowait,
            $this->arguments,
            $this->ticket
        );

        $this->getConnection()->getChannel()->basic_publish($message, '', $this->queueName, $this->routingKey);
    }
}