<?php

namespace Da\export\queue;

use Da\export\queue\JobInterface;

interface QueueStoreAdapterInterface
{
    /**
     * @return QueueStoreAdapterInterface
     */
    public function init();

    /**
     * @return AbstractQueueStoreConnection
     */
    public function getConnection();

    /**
     * @param JobInterface $job
     *
     * @return bool
     */
    public function enqueue(JobInterface $job);

    /**
     * @return MailJobInterface
     */
    public function dequeue();

    /**
     * @param JobInterface $mailJob
     */
    public function ack(JobInterface $mailJob);

    /**
     * @return bool
     */
    public function isEmpty();
}