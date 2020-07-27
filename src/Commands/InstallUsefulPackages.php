<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallUsefullPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:useful-packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '安装常用扩展';

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
        $this->runMigrate();
        
        $this->installPassport();
        $this->installHorizon();
        $this->installTelescope();
        // $this->installTinker();
        $this->installWebTinker();
    }

    public function runMigrate()
    {
        $this->call('php artisan migrate');
    }

    public function installPassport()
    {
        $this->call('php artisan passport:install');
    }

    public function installHorizon()
    {
        $this->call('php artisan horizon:install');
        $this->call('php artisan horizon:publish');
    }

    public function installTelescope()
    {
        $this->call('php artisan telescope:install');
        $this->call('php artisan telescope:publish');
    }

    public function installWebTinker()
    {
        $this->call('php artisan web-tinker:install');
        $this->call('php artisan vendor:publish --provider="Spatie\WebTinker\WebTinkerServiceProvider" --tag="config"');
    }
    
}
