<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\ModelMakeCommand;

class MakeControllerExtendsCrudControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud-controller {name}';

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
        $controllerName=$this->argument('name');
        if(count(explode('Controller',$controllerName))<=1){
            $this->warn('Controller name must end with \'Controller\'');
            return;
        }
        $controller = 'app/http/Controllers/' . $controllerName . '.php';
        $controllerFile = fopen($controller, 'w');


        $repository = 'use App\Repositories\\' . explode('Controller', $controllerName)[0] . 'Repository;';
        fwrite($controllerFile, '<?php');
        fwrite($controllerFile, "\n");
        fwrite($controllerFile, "\n");
        fwrite($controllerFile, 'namespace App\Http\Controllers;');
        fwrite($controllerFile, "\n");
        fwrite($controllerFile, "\n");
        fwrite($controllerFile, $repository);
        fwrite($controllerFile, "\n");
        fwrite($controllerFile, "\n");
        fwrite($controllerFile, "class " . $controllerName . " extends CrudController");
        fwrite($controllerFile, "\n");
        fwrite($controllerFile, "{");
        fwrite($controllerFile, "\n");
        fwrite($controllerFile, "\tpublic function __construct(". explode('Controller', $controllerName)[0] ."Repository \$repository)");

        fwrite($controllerFile, "\n");
        fwrite($controllerFile, "\t{");
        fwrite($controllerFile, "\n");
        fwrite($controllerFile, "\t\tparent::__construct(\$repository);");

        fwrite($controllerFile, "\n");
        fwrite($controllerFile, "\t}");
        fwrite($controllerFile, "\n");

        fwrite($controllerFile, "}");


        $this->info('Controller created successfully');

    }
}
