<h1>Dashboard</h1>

<div>
    <h2>Your Posts</h2>

    <?php if (!count($posts)): ?>
        You don't currently have any posts.
    <?php endif; ?>

    <?php foreach ($posts as $post): ?>
        <div>
            <a href="/post/<?= $post->getId() ?>"><?= $post->getTitle() ?></a>
            <a href="/post/delete/<?= $post->getId() ?>?csrfToken=<?= $csrfToken ?>">Delete Post</a>
            <a href="/post/edit/<?= $post->getId() ?>">Edit Post</a>
        </div>
    <?php endforeach; ?>
</div>
