<?php

namespace App\Console\Commands\Users;

use App\Jobs\User\FetchFollowers as FetchUserFollowers;
use Illuminate\Console\Command;

class FetchFollowers extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'followers:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch user followers from 500px.';

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
        \App\User::all()->each(function ($user) {
            $job = new FetchUserFollowers($user->getAttribute('id'));
            if ($user->getAttribute('followers_count') > 50000) {
                $job->onQueue('followers-heavy');
            }
            dispatch($job);
        });
    }
}
