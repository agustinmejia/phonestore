<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use TCG\Voyager\VoyagerServiceProvider;

class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phonestore:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize PhoneStore';

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
        $this->call('key:generate');
        $this->call('migrate:fresh');
        $this->call('db:seed');
        $this->call('storage:link');
        $this->call('vendor:publish', ['--provider' => VoyagerServiceProvider::class, '--tag' => ['config', 'voyager_avatar']]);
        File::copyDirectory('public/publishable/tipos-productos', 'public/storage/tipos-productos');
        $this->info('Gracias por instalar PhoneStore');
    }
}
