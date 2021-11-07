<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CustomFactoryMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-factory {name}';

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

        if (count(explode('Factory', $this->argument('name'))) <= 1) {
            $this->warn('Factory name must end with \'Factory\'');
            return;
        }
        $model = explode('Factory', $this->argument('name'))[0];

        $factory = 'database/factories/' . $this->argument('name') . '.php';
        $factoryFile = fopen($factory, 'w');

        fwrite($factoryFile, '<?php');
        fwrite($factoryFile, "\n");
        fwrite($factoryFile, "\n");
        fwrite($factoryFile, '/** @var \Illuminate\Database\Eloquent\Factory $factory */');
        fwrite($factoryFile, "\n");
        fwrite($factoryFile, "\n");
        if (file_exists("app\\$model.php")) {
            fwrite($factoryFile, "use App\\" . $model . ";");
            fwrite($factoryFile, "\n");
        }
        fwrite($factoryFile, "use Faker\Generator as Faker;");
        fwrite($factoryFile, "\n");
        fwrite($factoryFile, "\n");
        if (file_exists("app\\$model.php")) {
            fwrite($factoryFile, "\$factory->define($model::class, function (Faker \$faker) {");
        } else {

            fwrite($factoryFile, "\$factory->define(Model::class, function (Faker \$faker) {");
        }
        fwrite($factoryFile, "\n");
        fwrite($factoryFile, "\treturn [\n\t\t//\n\t];\n});");




        $this->info('Factory created successfully');

    }
}
