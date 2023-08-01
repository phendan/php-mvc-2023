<h1><?=$post->getTitle()?></h1>

<?php foreach ($post->getImages() as $image): ?>
    <img src="<?= $image ?>">
<?php endforeach; ?>

<p><?=$post->getBody()?></p>
<div>Posted on: <?=$post->getCreatedAt()?></div>
