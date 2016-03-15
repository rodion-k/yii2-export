<?php
namespace Da\export\queue\rabbitmq;

use commmon\extension\queue\JobInterface;
use common\extensions\queue\QueueStoreAdapterInterface;

class RabbitMqQueueStoreAdapter implements QueueStoreAdapterInterface
{
    /**
     * @var string the queue name
     */
    private $queueName;

    /**
     * @var string
     */
    private $routingKey;
    /**
     * @var RabbitMqQueueStoreConnection
     */
    protected $connection;

    /** @var bool */
    protected $passive=false;

    /** @var bool */
    protected $durable=false;

    /** @var bool */
    protected $exclusive=false;

    /** @var bool */
    protected $auto_delete=true;

    /** @var bool */
    protected $nowait=false;

    /** @var bool */
    protected $arguments=null;

    /** @var bool */
    protected $ticket=null;

    /**
     * RabbitMqQueueStoreAdapter constructor.
     *
     * @param RabbitMqQueueStoreConnection $connection
     * @param string $queueName
     */
    public function __construct(
        RabbitMqQueueStoreConnection $connection,
        $queueName = ''
    ) {
        $this->connection = $connection;
        $this->queueName = $queueName;
        $this->init();
    }

    /**
     * @return BeanstalkdQueueStoreAdapter
     */
    public function init()
    {
        $this->getConnection()->connect();

        return $this;
    }

    /**
     * @return BeanstalkdQueueStoreConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param JobInterface $job
     *
     * @return int
     */
    public function enqueue(JobInterface $job)
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

        $this->getConnection()->getChannel()->basic_publish($job->getMessage(), '', $this->queueName, $this->routingKey);
    }

    /**
     * @return JobInterface|null
     */
    public function dequeue()
    {
    }

    /**
     * @param JobInterface $mailJob
     */
    public function ack(JobInterface $mailJob)
    {
    }

    /**
     *
     * @return bool
     */
    public function isEmpty()
    {
    }
}