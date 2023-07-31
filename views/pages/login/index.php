<h1>Login</h1>

<?php if (isset($_SESSION['message'])): ?>
    <div class="message success"><?php echo $_SESSION['message']; ?></div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<form action="" method="post" novalidate>
    <?php if (isset($errors['root'])): ?>
        <?php foreach ($errors['root'] as $error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

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
        <input type="submit" value="Register">
    </div>
</form>
