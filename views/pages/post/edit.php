<h1>Edit Post</h1>

<form action="" method="post">
    <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">

    <div class="form-group">
        <?php if (isset($errors['title'])): ?>
            <?php foreach ($errors['title'] as $error): ?>
                <div class="message error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?= $post->getTitle() ?>">
    </div>

    <div class="form-group">
        <?php if (isset($errors['body'])): ?>
            <?php foreach ($errors['body'] as $error): ?>
                <div class="message error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <label for="body">Body</label>
        <textarea name="body" id="body">
            <?= $post->getBody() ?>
        </textarea>
    </div>

    <input type="submit" value="Edit Post">
</form>
