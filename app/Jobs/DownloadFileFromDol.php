<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
// Controller 
use App\Modules\Api\Http\Controllers\MediaController;
class DownloadFileFromDol implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $ID;
    protected  $UploadFileID;
    protected  $MediaController;
    public function __construct($ID,$UploadFileID)
    {
        $this->ID = $ID;
        $this->UploadFileID = $UploadFileID;
        $this->MediaController = new MediaController;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->MediaController->GetMeidaFileFromDol($this->ID,$this->UploadFileID);
    }
}
