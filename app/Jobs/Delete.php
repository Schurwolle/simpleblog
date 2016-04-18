<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class Delete extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $row;
    protected $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($row, $name)
    {
        $this->row = $row;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->row->delete();

        $mask = base_path().'/public/pictures/'.$this->name.'*';
        if (!empty($mask))
        {
            array_map('unlink', glob($mask));
        }
    }
}
