<?php

namespace App\Models;

use App\Helpers\Str;
use Exception;

class Post {
    private string $id;
    private string $title;
    private string $body;
    private string $createdAt;
    private string $updatedAt;
    private string $userId;
    private array $images;

    public function __construct(private Database $db)
    {}

    public function find(int $id): bool
    {
        $sql = "SELECT * FROM `posts` WHERE `id` = :id";
        $postQuery = $this->db->query($sql, [ 'id' => $id ]);

        if ($postQuery->rowCount() === 0) {
            return false;
        }

        $postData = $postQuery->results()[0];
        $this->setColumnsAsProperties($postData);

        return true;
    }

    private function setColumnsAsProperties(array $data)
    {
        foreach ($data as $column => $value) {
            $column = Str::toCamelCase($column);
            $this->{$column} = $value;
        }
    }

    public function create(int $userId, string $title, string $body, array $image)
    {
        $sql = "SELECT 1 FROM `posts` WHERE `title` = :title";
        $statement = $this->db->query($sql, [ 'title' => $title ]);

        if ($statement->rowCount() > 0) {
            throw new Exception("A post with the same title already exists.");
        }

        $sql = "
            INSERT INTO `posts`
            (`user_id`, `title`, `body`, `created_at`, `updated_at`)
            VALUES (:userId, :title, :body, :createdAt, :updatedAt)
        ";

        $this->db->query($sql, [
            'userId' => $userId,
            'title' => $title,
            'body' => $body,
            'createdAt' => time(),
            'updatedAt' => time()
        ]);

        $fileUpload = new FileUpload($image);
        $fileUpload->saveIn('images');
        $imageName = $fileUpload->getGeneratedName();

        $sql = "SELECT MAX(`id`) AS 'id' FROM `posts` WHERE `user_id` = :userId";
        $postIdQuery = $this->db->query($sql, [ 'userId' => $userId ]);
        $this->id = (int) $postIdQuery->results()[0]['id'];

        $sql = "
            INSERT INTO `post_images`
            (`post_id`, `path`, `alt_text`, `uploaded_at`)
            VALUES (:postId, :path, :altText, :uploadedAt)
        ";

        $this->db->query($sql, [
            'postId' => $this->id,
            'path' => "images/{$imageName}",
            'altText' => $title,
            'uploadedAt' => time()
        ]);
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getCreatedAt(): string
    {
        //Tue, 02.05.2023 20:14:23
        return date('D, d.m.Y H:i:s', $this->createdAt);
    }

    public function getUpdatedAt(): string
    {
        return date('D, d.m.Y H:i:s', $this->updatedAt);
    }

    public function getImages(): array
    {
        $sql = "SELECT `path` FROM `post_images` WHERE `post_id` = :postId";
        $imagesQuery = $this->db->query($sql, [ 'postId' => $this->getId() ]);

        $this->images = array_map(function($image) {
            return '/' . $image['path'];
        }, $imagesQuery->results());

        return $this->images;
    }

    public function toArray(): array
    {
        $data = [
            'title' => $this->getTitle(),
            'body' => $this->getBody(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'images' => $this->getImages()
        ];

        return $data;
    }
}
