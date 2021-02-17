<?php

namespace Geekyants\ShareDialog;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ShareDialogInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:share-dialog';

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
     * @return int
     */
    public function handle()
    {
    
        copy(__DIR__ . '/resources/js/share-dialog.js', resource_path('js/share-dialog.js'));
        copy(__DIR__ . '/resources/css/share-dialog.css', resource_path('css/share-dialog.css'));
        copy(__DIR__ . '/resources/views/share-dialog.blade.php', resource_path('views/share-dialog.blade.php'));
        tap(new Filesystem, function ($fs) {
            $fs->copyDirectory(__DIR__ . '/resources/js/Share-Dialog', resource_path('js/Share-Dialog'));
        });
        $this->info('Share-Dialog scaffolding installed successfully.');
        $this->info('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
    }
   
}
