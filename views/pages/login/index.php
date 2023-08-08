<h1>Login</h1>

<form action="" method="post" novalidate>
    <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">

    <div class="form-group">
        <?php if (isset($errors['username'])): ?>
            <?php foreach ($errors['username'] as $error): ?>
                <div class="message error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Your handle">
    </div>

    <div class="form-group">
        <?php if (isset($errors['password'])): ?>
            <?php foreach ($errors['password'] as $error): ?>
                <div class="message error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="form-group">
        <input type="submit" value="Login">
    </div>
</form>
