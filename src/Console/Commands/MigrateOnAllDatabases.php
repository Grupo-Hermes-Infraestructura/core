<?php

namespace Ghi\Core\Console\Commands;

use DB;
use Ghi\Core\Models\BaseDatosCadeco;
use Illuminate\Config\Repository;
use Illuminate\Console\Command;

class MigrateOnAllDatabases extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'migrate:alldb
	                        {connection : Conexion de base de datos donde se ejecutaran las migraciones.}
	                        {--path= : Ruta donde se encuentran las migraciones a ejecutar.}
	                        {--seed : Indicates if the seed task should be re-run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta las migraciones en todas las bases de datos de una conexion';

    /**
     * @var Repository
     */
    private $config;

    /**
     * Create a new command instance.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        parent::__construct();

        $this->config = $config;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Obtener las bases de datos
        $bds = BaseDatosCadeco::all();

        $this->output->progressStart(count($bds));

        foreach ($bds as $bd) {
            $this->output->newLine();
            $this->info('Ejecutando migraciones en ' . $bd->nombre);

            $this->config->set('database.connections.' . $this->argument('connection') . '.database', $bd->nombre);

            if ($this->option('path')) {
                $this->call('migrate', ['--database' => $this->argument('connection'), '--path' => $this->option('path')]);
            }

            $this->call('migrate', ['--database' => $this->argument('connection')]);

            if ($this->option('seed')) {
                $this->call('db:seed', ['--force' => true, '--database' => $this->argument('connection')]);
            }

            DB::disconnect($this->argument('connection'));

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }
}
