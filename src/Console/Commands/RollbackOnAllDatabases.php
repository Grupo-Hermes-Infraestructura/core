<?php

namespace Ghi\Core\Console\Commands;

use DB;
use Ghi\Core\Models\BaseDatosCadeco;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;

class RollbackOnAllDatabases extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'migrate:alldb:rollback
	                        {connection} : Conexion de base de datos donde se ejecutaran las migraciones.';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revierte la ultima migracion en todas las bases de datos de una conexion';

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
            $this->info('Revirtiendo la ultima migracion en ' . $bd->nombre);

            $this->config->set('database.connections.' . $this->argument('connection') . '.database', $bd->nombre);

            $this->call('migrate:rollback', ['--database' => $this->argument('connection')]);

            DB::disconnect($this->argument('connection'));

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }
}
