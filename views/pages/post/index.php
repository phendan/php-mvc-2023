<h1><?=$post->getTitle()?></h1>

<?php foreach ($post->getImages() as $image): ?>
    <img src="<?= $image ?>">
<?php endforeach; ?>

<p><?=$post->getBody()?></p>
<div>Posted on: <?=$post->getCreatedAt()?></div>
<div>Likes: <?=$post->getLikeCount()?></div>

<?php if ($user->isLoggedIn()): ?>
    <?php if ($post->isLikedBy($user)): ?>
        <a href="/post/dislike/<?= $post->getId() ?>?csrfToken=<?= $csrfToken ?>">Dislike</a>
    <?php else: ?>
        <a href="/post/like/<?= $post->getId() ?>?csrfToken=<?= $csrfToken ?>">Like</a>
    <?php endif; ?>
<?php endif; ?>
