<?php


namespace Tests\Unit\Stubs;


class FiveHundredPxPhotosCall
{

    /**
     * @var int
     */
    public $page;
    /**
     * @var array
     */
    public $photos;
    /**
     * @var int
     */
    public $total_items;
    /**
     * @var int
     */
    public $total_pages;
    /**
     * @var string
     */
    public $feature;

    /**
     * FiveHundredPxUsersPhotosCall constructor.
     * @param int $rpp
     * @param int $page
     * @param int $nbPages
     * @param array $photoSizes
     * @param string $feature
     */
    public function __construct(
        int $rpp,
        int $page,
        int $nbPages,
        array $photoSizes = [3, 5],
        string $feature = 'popular'
    ) {
        $this->total_items = $rpp * $nbPages;
        $this->page = $page;
        $this->total_pages = $nbPages;
        $this->photos = [];
        $this->feature = $feature;

        for ($i = 0; $i < $rpp; $i++) {
            $this->photos[] = new FiveHundredPxPhotoObject(...$photoSizes);
        }
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page)
    {
        $this->page = $page;
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @param int $key
     * @return FiveHundredPxPhotoObject
     */
    public function getPhoto(int $key): FiveHundredPxPhotoObject
    {
        return $this->photos[$key];
    }

    /**
     * @param array $photos
     */
    public function setPhotos(array $photos)
    {
        $this->photos = $photos;
    }

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->total_items;
    }

    /**
     * @param int $total_items
     */
    public function setTotalItems(int $total_items)
    {
        $this->total_items = $total_items;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->total_pages;
    }

    /**
     * @param int $total_pages
     */
    public function setTotalPages(int $total_pages)
    {
        $this->total_pages = $total_pages;
    }

    /**
     * @return string
     */
    public function getFeature(): string
    {
        return $this->feature;
    }

    /**
     * @param string $feature
     */
    public function setFeature(string $feature)
    {
        $this->feature = $feature;
    }

}