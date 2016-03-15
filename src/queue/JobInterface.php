<?php
namespace Da\export\queue;

interface JobInterface
{
    /**
     * @return bool whether the mail job is a new instance or has been extracted from queue
     */
    public function isNewRecord();
    /**
     * @param string $message
     */
    public function setMessage($message);
    /**
     * @return string
     */
    public function getMessage();
    /**
     * @return bool whether the job has been successfully completed
     */
    public function isCompleted();
}