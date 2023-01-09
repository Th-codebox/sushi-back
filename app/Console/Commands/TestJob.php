<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use PHPUnit\Util\Test;

/**
 * Class ClearApp
 * @package App\Console\Commands
 */
class TestJob extends Command
{

    protected $signature = 'testjob';

    protected $description = 'testjob';

    use DispatchesJobs;

    /**
     * ClearApp constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->dispatch(new \App\Jobs\TestJob());
    }
}
