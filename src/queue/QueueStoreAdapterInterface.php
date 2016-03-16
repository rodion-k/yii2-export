<?php

namespace Da\export\queue;

interface QueueStoreAdapterInterface
{
    /**
     * @return AbstractQueueStoreConnection
     */
    public function getConnection();

    /**
     * @param string $message
     *
     * @return bool
     */
    public function enqueue($message);
}