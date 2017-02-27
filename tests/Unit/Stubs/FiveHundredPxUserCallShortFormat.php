<?php


namespace Tests\Unit\Stubs;

use Faker\Factory;


class FiveHundredPxUserCallShortFormat
{

    public $id;
    public $username;
    public $firstname;
    public $lastname;
    public $city;
    public $country;
    public $usertype;
    public $fullname;
    public $userpic_url;
    public $userpic_https_url;
    public $cover_url;
    public $upgrade_status;
    public $store_on;
    public $affection;
    public $avatars;
    public $followers_count;
    public $thumbnail_background_url;

    public function __construct()
    {
        $faker = Factory::create();
        $this->id = $faker->randomNumber(8);
        $this->username = $faker->userName;
        $this->firstname = $faker->boolean(90) ? $faker->firstName : "";
        $this->lastname = $faker->boolean(70) ? $faker->lastName : "";
        $this->city = $faker->boolean(70) ? $faker->city : "";
        $this->country = $faker->boolean(70) ? $faker->country : "";
        $this->usertype = $faker->numberBetween(0, 2);
        $this->fullname = $this->username;
        $this->userpic_url = "http://fillmurray.com/200/200";
        $this->userpic_https_url = "http://fillmurray.com/200/200";
        $this->cover_url = "http://fillmurray.com/1280/800";
        $this->upgrade_status = $faker->numberBetween(0, 2);;
        $this->store_on = $faker->boolean(20);
        $this->affection = $faker->randomDigit;
        $this->followers_count = $faker->randomDigit;
        $this->thumbnail_background_url = "http://fillmurray.com/200/200";

        $avatar = new \stdClass();
        $avatar->https = "http://fillmurray.com/200/200";
        $this->avatars = new \stdClass();
        $this->avatars->large = $avatar;
        $this->avatars->small = $avatar;
        $this->avatars->tiny = $avatar;
        $this->avatars->default = $avatar;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    /**
     * @return int
     */
    public function getUsertype(): int
    {
        return $this->usertype;
    }

    /**
     * @param int $usertype
     */
    public function setUsertype(int $usertype)
    {
        $this->usertype = $usertype;
    }

    /**
     * @return string
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     */
    public function setFullname(string $fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * @return string
     */
    public function getUserpicUrl(): string
    {
        return $this->userpic_url;
    }

    /**
     * @param string $userpic_url
     */
    public function setUserpicUrl(string $userpic_url)
    {
        $this->userpic_url = $userpic_url;
    }

    /**
     * @return string
     */
    public function getUserpicHttpsUrl(): string
    {
        return $this->userpic_https_url;
    }

    /**
     * @param string $userpic_https_url
     */
    public function setUserpicHttpsUrl(string $userpic_https_url)
    {
        $this->userpic_https_url = $userpic_https_url;
    }

    /**
     * @return string
     */
    public function getCoverUrl(): string
    {
        return $this->cover_url;
    }

    /**
     * @param string $cover_url
     */
    public function setCoverUrl(string $cover_url)
    {
        $this->cover_url = $cover_url;
    }

    /**
     * @return int
     */
    public function getUpgradeStatus(): int
    {
        return $this->upgrade_status;
    }

    /**
     * @param int $upgrade_status
     */
    public function setUpgradeStatus(int $upgrade_status)
    {
        $this->upgrade_status = $upgrade_status;
    }

    /**
     * @return bool
     */
    public function isStoreOn(): bool
    {
        return $this->store_on;
    }

    /**
     * @param bool $store_on
     */
    public function setStoreOn(bool $store_on)
    {
        $this->store_on = $store_on;
    }

    /**
     * @return int
     */
    public function getAffection(): int
    {
        return $this->affection;
    }

    /**
     * @param int $affection
     */
    public function setAffection(int $affection)
    {
        $this->affection = $affection;
    }

    /**
     * @return \stdClass
     */
    public function getAvatars(): \stdClass
    {
        return $this->avatars;
    }

    /**
     * @param \stdClass $avatars
     */
    public function setAvatars(\stdClass $avatars)
    {
        $this->avatars = $avatars;
    }

    /**
     * @return int
     */
    public function getFollowersCount(): int
    {
        return $this->followers_count;
    }

    /**
     * @param int $followers_count
     */
    public function setFollowersCount(int $followers_count)
    {
        $this->followers_count = $followers_count;
    }

    /**
     * @return string
     */
    public function getThumbnailBackgroundUrl(): string
    {
        return $this->thumbnail_background_url;
    }

    /**
     * @param string $thumbnail_background_url
     */
    public function setThumbnailBackgroundUrl(string $thumbnail_background_url)
    {
        $this->thumbnail_background_url = $thumbnail_background_url;
    }
}