<?php

namespace App\Console\Commands;

use App\Classes\EmailHelpers;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle()
    {
        $helper = new EmailHelpers('garstikaitis@gmail.com', '9pq3enl6q042vwrz', [['organization' => ['name' => 'test']]]);
        $helper->sendEmail();
    }
}
