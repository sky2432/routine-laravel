<?php

namespace App\Console\Commands;

use App\Models\Routine;
use App\Services\CountService;
use Illuminate\Console\Command;

class UpdateRecordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:record';

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
        $routines = Routine::all();

        foreach ($routines as $routine) {
            CountService::updateRoutineCountData($routine->id);
        }
    }
}
