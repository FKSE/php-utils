<?php
namespace FKSE\Process;

use Symfony\Component\Process\Process;

/**
 * Provides a thread pool like approach for the Symfony2 process component
 *
 * @author Fridolin Koch <info@fridokoch.de>
 */
class ProcessPool
{
    const SLEEP_INTERVAL = 500000; //0.5 sec
    /**
     * @var int The amount of concurrent processes
     */
    protected $concurrent;

    /**
     * @var Process[]
     */
    protected $processes;

    /**
     * @var string[]
     */
    protected $status;

    /**
     * @var string[]
     */
    protected $previousStatus;

    /**
     * @var callable
     */
    protected $updateCallback;

    /**
     * @param int $concurrent Maximum concurrent processes
     */
    public function __construct($concurrent)
    {
        $this->concurrent = $concurrent;
        //status arrays
        $this->status = [];
        $this->previousStatus = [];
    }

    /**
     * @return int
     */
    public function getConcurrent()
    {
        return $this->concurrent;
    }

    /**
     * @param int $concurrent
     */
    public function setConcurrent($concurrent)
    {
        $this->concurrent = $concurrent;
    }

    /**
     * The given function is called every time a process changes its state
     *
     * @param callable $callback
     */
    public function onStatusUpdate(callable $callback)
    {
        $this->updateCallback = $callback;
    }

    /**
     * Adds a Process to the process pool
     *
     * @param Process $process The process which should be added to the process pool
     */
    public function add(Process $process)
    {
        $this->processes[] = $process;
    }

    /**
     * Start the processes in the pool
     */
    public function start()
    {
        foreach ($this->processes as $process) {
            //start process
            $process->start();

            //update status array
            $this->updateStatus();

            while ($this->runningProcesses() >= $this->concurrent) {
                usleep(self::SLEEP_INTERVAL);
                //update status
                $this->updateStatus();
            }
        }
        //wait until all processes are finished
        $this->wait();
    }

    /**
     * Wait until all processes are finished
     */
    protected function wait()
    {
        while ($this->runningProcesses() > 0) {
            usleep(self::SLEEP_INTERVAL);
            //update status
            $this->updateStatus();
        }
    }

    /**
     * Counts the number of running processes in the pool
     *
     * @return int The number of currently running processes
     */
    protected function runningProcesses()
    {
        $count = 0;
        foreach ($this->processes as $process) {
            if ($process->isRunning()) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Updates the status of all processes
     *
     * @param bool $alwaysNotify
     */
    protected function updateStatus($alwaysNotify = false)
    {
        //save current
        $this->previousStatus = $this->status;
        //clear status
        $this->status = [];
        foreach ($this->processes as $process) {
            $this->status[] = $process->getStatus();
        }
        //notify if something has changed
        if ($this->previousStatus != $this->status || $alwaysNotify) {
            if (is_callable($this->updateCallback)) {
                call_user_func($this->updateCallback, $this->status);
            }
        }

    }

}
