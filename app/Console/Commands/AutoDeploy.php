<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoDeploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'git:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Git deploy base';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        exec('git reset --hard');
        exec('git pull origin master');
        exec('chmod -R 777 storage');
        exec('chmod -R 777 bootstrap/cache');
        exec('composer install');

        return 0;
    }
}
