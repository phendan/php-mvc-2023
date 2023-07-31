<main>
    <h1>Register</h1>

    <form action="" method="post" novalidate>
        <?php if (isset($errors['root'])): ?>
            <?php foreach ($errors['root'] as $error): ?>
                <div class="message error"><?=$error?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="form-group">
            <?php if (isset($errors['email'])): ?>
                <?php foreach ($errors['email'] as $error): ?>
                    <div class="message error"><?=$error?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="me@somewhere.com">
        </div>

        <div class="form-group">
            <?php if (isset($errors['username'])): ?>
                <?php foreach ($errors['username'] as $error): ?>
                    <div class="message error"><?=$error?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Your handle">
        </div>

        <div class="form-group">
            <?php if (isset($errors['password'])): ?>
                <?php foreach ($errors['password'] as $error): ?>
                    <div class="message error"><?=$error?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>

        <div class="form-group">
            <?php if (isset($errors['passwordAgain'])): ?>
                <?php foreach ($errors['passwordAgain'] as $error): ?>
                    <div class="message error"><?=$error?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <label for="password-again">Repeat Password</label>
            <input type="password" name="passwordAgain" id="password-again">
        </div>

        <div class="form-group">
            <label for="terms-of-service">Terms of Service</label>
            <input type="checkbox" name="termsOfService" id="terms-of-service">
        </div>

        <div class="form-group">
            <input type="submit" value="Register">
        </div>
    </form>
</main>
