<?php

namespace App\Console\Commands\Comments;

use App\Jobs\User\FetchPhotos as FetchNewPhotos;
use Illuminate\Console\Command;

class FetchPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch new photos from 500px.';

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
               new FetchNewPhotos($user->getAttribute('id'))
           ); 
        });
    }
}
