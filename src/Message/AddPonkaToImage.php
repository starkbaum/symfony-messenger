<?php


namespace App\Message;



class AddPonkaToImage
{
    private $imagePostId;

    public function __construct(int $imagePostId)
    {
        $this->imagePostId = $imagePostId;
    }

    /**
     * Returns the id of an imagePost
     *
     * @return int
     */
    public function getImagePostId(): int
    {
        return $this->imagePostId;
    }
}