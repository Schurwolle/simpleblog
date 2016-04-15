<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class DeleteArticleImages extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $articleID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($articleID)
    {
        $this->articleID = $articleID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mask = base_path().'/public/pictures/'.$this->articleID.'*';
        if (!empty($mask))
        {
            array_map('unlink', glob($mask));
        }
    }
}
