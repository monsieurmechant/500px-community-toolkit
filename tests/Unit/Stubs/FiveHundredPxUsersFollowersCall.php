<?php


namespace Tests\Unit\Stubs;


class FiveHundredPxUsersFollowersCall
{

    public $page;
    public $followers;
    public $followers_count;
    public $followers_pages;

    public function __construct($rpp, $page, $nbPages)
    {
        $this->followers_count = $rpp * $nbPages;
        $this->page = $page;
        $this->followers_pages = $nbPages;
        $this->followers = [];

        for($i = 0; $i < $rpp; $i++) {
            $this->followers[] = new FiveHundredPxUserCallShortFormat();
        }
    }

}