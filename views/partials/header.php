<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP OOP MVC <?php echo $title ?? '' ?></title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/about-us">About Us</a></li>
                <?php if ($user->isLoggedIn()): ?>
                    <li><a href="/post/create">Create Post</a></li>
                    <li><a href="/logout">Sign Out</a></li>
                <?php else: ?>
                    <li><a href="/login">Sign In</a></li>
                    <li><a href="/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
