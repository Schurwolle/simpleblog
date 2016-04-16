<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Intervention\Image\ImageManager;

class UploadAvatar extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

        protected $photo;
        protected $fileName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($photo, $fileName)
    {
        $this->photo = $photo;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $manager = new ImageManager();
            $image = $manager->make(base_path().'/public/'.$this->photo)->save(base_path().'/public/pictures/'.$this->fileName);
    }
}
