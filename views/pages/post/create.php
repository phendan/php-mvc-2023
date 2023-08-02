<h1>Write Your Post</h1>

<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">

    <div class="form-group">
        <?php if (isset($errors['title'])): ?>
            <?php foreach ($errors['title'] as $error): ?>
                <div class="message error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <label for="title">Title</label>
        <input type="text" name="title" id="title">
    </div>

    <div class="form-group">
        <?php if (isset($errors['body'])): ?>
            <?php foreach ($errors['body'] as $error): ?>
                <div class="message error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <label for="body">Body</label>
        <textarea name="body" id="body"></textarea>
    </div>

    <div class="form-group">
        <?php if (isset($errors['image'])): ?>
            <?php foreach ($errors['image'] as $error): ?>
                <div class="message error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <label for="image">Image</label>
        <input type="file" name="image" id="image">
    </div>

    <input type="submit" value="Create Post">
</form>
