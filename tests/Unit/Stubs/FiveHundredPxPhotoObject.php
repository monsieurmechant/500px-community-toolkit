<?php


namespace Tests\Unit\Stubs;

use Faker\Factory;


class FiveHundredPxPhotoObject
{

    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var int
     */
    public $times_viewed;
    /**
     * @var float
     */
    public $rating;
    /**
     * @var string
     */
    public $created_at;
    /**
     * @var int
     */
    public $category;
    /**
     * @var bool
     */
    public $privacy;
    /**
     * @var int
     */
    public $width;
    /**
     * @var int
     */
    public $height;
    /**
     * @var int
     */
    public $votes_count;
    /**
     * @var int
     */
    public $comments_count;
    /**
     * @var bool
     */
    public $nsfw;
    /**
     * @var string
     */
    public $image_url;
    /**
     * @var FiveHundredPxUserCallShortFormat
     */
    public $user;
    /**
     * @var array
     */
    public $images;
    /**
     * @var string
     */
    public $url;

    /**
     * FiveHundredPxUserCallShortFormat constructor.
     * @param \int[] $imageSizes
     */
    public function __construct(int ...$imageSizes)
    {
        $faker = Factory::create();

        $this->id = $faker->randomNumber(8);
        $this->name = $faker->userName;
        $this->description = $faker->text(30);
        $this->times_viewed = $faker->numberBetween(0, 150000);
        $this->rating = $faker->randomFloat(1, 50, 100);
        $this->created_at = \Carbon\Carbon::instance($faker->dateTimeThisYear)->setTimezone('America/Toronto')->toAtomString();
        $this->category = $faker->numberBetween(0, 27);
        $this->privacy = $faker->boolean(95);
        $this->width = $faker->numberBetween(0, 1800);
        $this->height = $faker->numberBetween(0, 1800);
        $this->votes_count = $faker->numberBetween(0, 2500);
        $this->comments_count = $faker->numberBetween(0, 40);
        $this->nsfw = $faker->boolean(20);
        $this->image_url = $faker->imageUrl();
        $this->url = $faker->url;
        $this->user = new FiveHundredPxUserCallShortFormat();
        $this->images = array_map(function ($size) use ($faker) {
            $image = new \stdClass();
            $image->size = $size;
            $image->url = $image->https_url = $faker->imageUrl();
            $image->format = "jpeg";
            return $image;
        }, $imageSizes);
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getTimesViewed(): int
    {
        return $this->times_viewed;
    }

    /**
     * @param int $times_viewed
     */
    public function setTimesViewed(int $times_viewed)
    {
        $this->times_viewed = $times_viewed;
    }

    /**
     * @return float
     */
    public function getRating(): float
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     */
    public function setRating(float $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt(string $created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return int
     */
    public function getCategory(): int
    {
        return $this->category;
    }

    /**
     * @param int $category
     */
    public function setCategory(int $category)
    {
        $this->category = $category;
    }

    /**
     * @return bool
     */
    public function getPrivacy(): bool
    {
        return $this->privacy;
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->privacy;
    }

    /**
     * @param bool $privacy
     */
    public function setPrivacy(bool $privacy)
    {
        $this->privacy = $privacy;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getVotesCount(): int
    {
        return $this->votes_count;
    }

    /**
     * @param int $votes_count
     */
    public function setVotesCount(int $votes_count)
    {
        $this->votes_count = $votes_count;
    }

    /**
     * @return int
     */
    public function getCommentsCount(): int
    {
        return $this->comments_count;
    }

    /**
     * @param int $comments_count
     */
    public function setCommentsCount(int $comments_count)
    {
        $this->comments_count = $comments_count;
    }

    /**
     * @return bool
     */
    public function isNsfw(): bool
    {
        return $this->nsfw;
    }

    /**
     * @param bool $nsfw
     */
    public function setNsfw(bool $nsfw)
    {
        $this->nsfw = $nsfw;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    /**
     * @param string $image_url
     */
    public function setImageUrl(string $image_url)
    {
        $this->image_url = $image_url;
    }

    /**
     * @return FiveHundredPxUserCallShortFormat
     */
    public function getUser(): FiveHundredPxUserCallShortFormat
    {
        return $this->user;
    }

    /**
     * @param FiveHundredPxUserCallShortFormat $user
     */
    public function setUser(FiveHundredPxUserCallShortFormat $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param array $images
     */
    public function setImages(array $images)
    {
        $this->images = $images;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

}