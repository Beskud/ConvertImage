<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use WebPConvert\WebPConvert;


class UploadImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wasabi:save_img';

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
    private $disk,$webp_folder;

    const WEB_FOLDER = 'webp_folder';

    public function __construct()
    {
        parent::__construct();
        $this->disk = Storage::disk('wasabi');
        $this->webp_folder = public_path(self::WEB_FOLDER);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!is_dir($this->webp_folder)) mkdir($this->webp_folder, 0700,true);

        foreach (glob(public_path('images') . '/*.jpg') as $fileName) {
            $info = pathinfo($fileName);
            $new_wbp =  $this->webp_folder.'/'.$info["filename"].'.webp';
            WebPConvert::convert($fileName,$new_wbp, []);
            $this->disk->put($info["filename"].'.webp',file_get_contents($new_wbp));
            unlink($new_wbp);
        }
        rmdir($this->webp_folder);
    }
}
