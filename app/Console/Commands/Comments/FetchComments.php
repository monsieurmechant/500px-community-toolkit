<?php

namespace App\Console\Commands\Comments;

use App\Jobs\User\FetchNewComments;
use Illuminate\Console\Command;

class FetchComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comments:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch new comments from 500px.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \App\User::all()->each(function($user) {
           dispatch(
               new FetchNewComments($user)
           ); 
        });
    }
}
