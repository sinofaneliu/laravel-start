<?php

namespace Sinofaneliu\LaravelStart\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class CreateModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:model {name} {--dir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加一套模型全家桶';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->dir = $this->option('dir');
        $name = class_basename($name);
        $this->variable = Str::snake($name);
        $this->table_name = Str::plural($this->variable);
        $this->model_name = Str::studly($name);

        $this->createMigration(); 
        $this->createModel();
        $this->createPolicy();
        $this->createRequests();
        $this->createController();
        $this->createFilter();
        $this->createResources();
    }

    public function createMigration()
    {
        $table = $this->table_name;

        $this->call('make:migration', [
            'name' => "{$table}_create",
            '--create' => $table,
        ]);
    }

    public function createModel()
    {
        $model = __DIR__.'/stubs/Model/Model.stub';
        $path = app_path().'/Models/'.$this->model_name.'.php';
        $this->createFiles($model, $path);
    }

    public function createPolicy()
    {
        $policy = __DIR__.'/stubs/Policy/Policy.stub';
        $path = app_path().'/Policies/'.$this->model_name.'Policy.php';
        $this->createFiles($policy, $path);
    }

    public function createRequests()
    {
        $requestPath = __DIR__.'/stubs/Requests/';
        $path = app_path().'/Http/Requests/'.$this->model_name.'/';
        
        $requests = $this->files->files($requestPath);

        foreach ($requests as $request) {
            $name = $this->files->name($request);
            $cur_path = $path.$name.'.php';
            $this->createFiles($request, $cur_path);
        }
    }

    public function createController()
    {
        $controller = __DIR__.'/stubs/Controller/Controller.stub';
        $path = app_path().'/Http/Controllers/template/'.$this->model_name.'Controller.php';
        $this->createFiles($controller, $path);
    }

    public function createFilter()
    {
        $filter = __DIR__.'/stubs/Filter/Filter.stub';
        $path = app_path().'/Http/Filters/'.$this->model_name.'Filter.php';
        $this->createFiles($filter, $path);
    }

    public function createResources()
    {
        $resourcePath = __DIR__.'/stubs/Resources/';
        $path = app_path().'/Http/Resources/'.$this->model_name.'/';
        
        $resources = $this->files->files($resourcePath);

        foreach ($resources as $resource) {
            $name = $this->files->name($resource);
            $cur_path = $path.$this->model_name.$name.'.php';
            $this->createFiles($resource, $cur_path);
        }
    }

    protected function createFiles($stub, $path)
    {
        $stub = $this->files->get($stub);
        $stub = $this->replaceName($stub);
        if($this->files->exists($path)){
            $this->error($path.'已存在！');
        }else{
            $this->makeDirectory($path);
            $this->files->put($path, $stub);
            $this->info($path.'文件创建成功');
        }
    }

    protected function replaceName($stub)
    {
        $stub = str_replace(
            ['__MODEL_NAME__', '__TABLE_NAME__', '__VARIABLE__', '__DIR_NAME__'],
            [$this->model_name, $this->table_name, $this->variable, $this->dir],
            $stub
        );
        return $stub;
    }

    protected function makeDirectory($path)
    {
        $dir = dirname($path);
        if (!$this->files->isDirectory($dir)) {
            try{
                $this->files->makeDirectory($dir);
            } catch (\Exception $e) {
                $this->makeDirectory($dir);
                $this->makeDirectory($path);
                return;
            }
            $this->info($dir.'目录已创建');
        }
    }
}
