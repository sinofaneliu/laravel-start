<?php

namespace Sinofaneliu\LaravelStart\Commands;

use Illuminate\Console\Command;

class InstallUsefulPackages extends Command
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
        $this->call('migrate');
    }

    public function installPassport()
    {
        $this->call('passport:install');
    }

    public function installHorizon()
    {
        $this->call('horizon:install');
        $this->call('horizon:publish');
    }

    public function installTelescope()
    {
        $this->call('telescope:install');
        $this->call('telescope:publish');
    }

    public function installWebTinker()
    {
        $this->call('web-tinker:install');
        $this->call('vendor:publish --provider="Spatie\WebTinker\WebTinkerServiceProvider" --tag="config"');
    }
    
}
