<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class ClearApp
 * @package App\Console\Commands
 */
class ClearApp extends Command
{

    protected $signature = 'clear:app';

    protected $description = 'Clear all app';

    /**
     * ClearApp constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {

        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('route:clear');
    }
}
