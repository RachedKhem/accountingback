<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RepositoryMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

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

        if (count(explode('Repository', $this->argument('name'))) <= 1) {
            $this->warn('Repository name must end with \'Repository\'');
            return;
        }

        if (!file_exists('app/Repositories')) {
            mkdir('app/Repositories');
        }
        $model = explode('Repository', $this->argument('name'))[0];

        $repository = 'app/Repositories/' . $this->argument('name') . '.php';
        $repositoryFile = fopen($repository, 'w');

        fwrite($repositoryFile, '<?php');
        fwrite($repositoryFile, "\n");
        fwrite($repositoryFile, "\n");
        fwrite($repositoryFile, 'namespace App\Repositories;');
        fwrite($repositoryFile, "\n");
        fwrite($repositoryFile, "\n");
        if (file_exists("app\\$model.php")) {
            fwrite($repositoryFile, "use App\\" . $model . ";");
            fwrite($repositoryFile, "\n");
            fwrite($repositoryFile, "\n");
        }
        fwrite($repositoryFile, "class " . $this->argument('name') . " extends CrudRepository");
        fwrite($repositoryFile, "\n");
        fwrite($repositoryFile, "{");
        fwrite($repositoryFile, "\n");

        if (file_exists("app\\$model.php")) {
            fwrite($repositoryFile, "\tpublic function __construct(" . $model . " \$model)");

            fwrite($repositoryFile, "\n");
            fwrite($repositoryFile, "\t{");
            fwrite($repositoryFile, "\n");
            fwrite($repositoryFile, "\t\tparent::__construct(\$model);");

            fwrite($repositoryFile, "\n");
            fwrite($repositoryFile, "\t}");
        }
        fwrite($repositoryFile, "\n");

        fwrite($repositoryFile, "}");


        $this->info('Repository created successfully');

    }
}
