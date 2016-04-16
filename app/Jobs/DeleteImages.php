<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class DeleteImages extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mask = base_path().'/public/pictures/'.$this->name.'*';
        if (!empty($mask))
        {
            array_map('unlink', glob($mask));
        }
    }
}
