<?php

namespace App\Jobs;

use App\Http\Controllers\FrontEnd\Curriculum\Enrollment\EnrolmentController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CourseEnrolmentConfirm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $enrolmentInfo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($enrolmentInfo)
    {
        $this->enrolmentInfo = $enrolmentInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $enrol = new EnrolmentController();

        // send a mail to the customer with the invoice
        $enrol->sendMail($this->enrolmentInfo);
    }
}
