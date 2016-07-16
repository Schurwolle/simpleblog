<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteCKEImages extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $old_imgs;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($old_imgs, $body)
    {
        $this->old_imgs = $old_imgs;
        $this->body     = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->old_imgs as $old_img)
        {
            if(preg_match('*'.$old_img.'*', $this->body) === 0)
            {
                unlink(base_path().'/public/'.$old_img);
            }
        }
    }
}
