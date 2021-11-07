<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Laravel\Passport\Console\InstallCommand;

class InitProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:project';

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
     * @return mixed
     */
    public function handle()
    {
        foreach (scandir('public/uploads/') as $dir) {
            if ($dir != '.' && $dir != '..')
                $this->deleteDirectory('public/uploads/' . $dir);
        }
        $this->call(FreshCommand::class,['--seed'=>true]);
        $this->call(InstallCommand::class,['--force'=>true]);
    }

    private function deleteDirectory($path)
    {
        if (is_dir($path)) {
            foreach (scandir($path) as $file) {
                if ($file != '.' && $file != '..') {
                    $this->deleteDirectory($path . '/' . $file);
                }
            }
            rmdir($path);

        } else {
            unlink($path);
        }
    }
}
