<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Foundation\Console\ModelMakeCommand;

class CrudMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name}';

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
        $this->call(ModelMakeCommand::class, ['name' => $this->argument('name'), '-m' => true]);
        $this->call(RepositoryMakeCommand::class,['name' => $this->argument('name').'Repository']);
        $this->call(MakeControllerExtendsCrudControllerCommand::class,['name' => $this->argument('name').'Controller']);
        $this->call(CustomFactoryMakeCommand::class,['name'=>$this->argument('name').'Factory']);
        $this->call(SeederMakeCommand::class,['name'=>$this->argument('name').'Seeder']);
    }
}
