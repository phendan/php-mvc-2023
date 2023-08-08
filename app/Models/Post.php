<?php

namespace App\Models;

use App\Helpers\Str;
use App\Helpers\Exception;

use App\Traits\RepresentsDatabaseEntry;

class Post {
    use RepresentsDatabaseEntry;

    private string $id;
    private string $title;
    private string $body;
    private string $createdAt;
    private string $updatedAt;
    private string $userId;
    private array $images;

    public function __construct(private Database $db, ?array $data = [])
    {
        $this->setColumnsAsProperties($data);
    }

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

    public function create(int $userId, string $title, string $body, array $image)
    {
        $sql = "SELECT 1 FROM `posts` WHERE `title` = :title";
        $statement = $this->db->query($sql, [ 'title' => $title ]);

        if ($statement->rowCount() > 0) {
            throw new Exception(data: [ 'title' => ["A post with the same title already exists."] ]);
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

    public function edit(string $title, string $body): bool
    {
        $sql = "
            UPDATE `posts`
            SET `title` = :title, `body` = :body, `updated_at` = :updatedAt
            WHERE `id` = :id
        ";

        $postData = [
            'id' => $this->getId(),
            'title' => $title,
            'body' => $body,
            'updatedAt' => time()
        ];

        $editQuery = $this->db->query($sql, $postData);
        $this->setColumnsAsProperties($postData);

        return (bool) $editQuery->rowCount();
    }

    public function delete(): bool
    {
        $images = $this->getImages();

        foreach ($images as $image) {
            FileUpload::delete($image);
        }

        $sql = "DELETE FROM `posts` WHERE `id` = :id";
        $deleteQuery = $this->db->query($sql, [ 'id' => $this->getId() ]);

        return (bool) $deleteQuery->rowCount();
    }

    public function like(int $userId): bool
    {
        $sql = "INSERT INTO `post_likes` (`user_id`, `post_id`) VALUES (:userId, :postId)";

        try {
            $likeQuery = $this->db->query($sql, [
                'userId' => $userId,
                'postId' => $this->getId()
            ]);
        } catch (Exception $e) {
            return false;
        }

        return (bool) $likeQuery->rowCount();
    }

    public function dislike(int $userId): bool
    {
        $sql = "DELETE FROM `post_likes` WHERE `post_id` = :postId AND `user_id` = :userId";

        $dislikeQuery = $this->db->query($sql, [
            'userId' => $userId,
            'postId' => $this->getId()
        ]);

        return (bool) $dislikeQuery->rowCount();
    }

    public function getLikeCount(): int
    {
        $sql = "SELECT COUNT(`id`) as 'like_count' FROM `post_likes` WHERE `post_id` = :postId";

        $likesQuery = $this->db->query($sql, [ 'postId' => $this->getId() ]);

        $firstRow = $likesQuery->results()[0];

        return (int) $firstRow['like_count'];
    }

    public function isLikedBy(User $user): bool
    {
        $sql = "SELECT 1 FROM `post_likes` WHERE `user_id` = :userId AND `post_id` = :postId";

        $likeQuery =  $this->db->query($sql, [
            'postId' => $this->getId(),
            'userId' => $user->getId()
        ]);

        return (bool) $likeQuery->rowCount();
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

    public function getUserId(): int
    {
        return (int) $this->userId;
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
