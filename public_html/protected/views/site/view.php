<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div style="height:50px"><h1 style="width: 50%; float: left;">Movie details</h1></div>

<?php
if (isset($error)) {
?>
    <h2><?php echo $error; ?></h2>
<?php
} else if (isset($movie)) {
?>
        <div class="full">
            <div style="float: left;">
            <?php if ($movie->poster_path) { ?>
            <img src="<?php echo $configurations->images->base_url; ?>/w185/<?php echo $movie->poster_path; ?>" class="crop" />
            <?php } else { ?>
            <img src="http://placehold.it/185x278/eee/ccc?text=No Image" class="crop" />
            <?php } ?>
            </div>
            <div style="float: left; padding-left: 20px; width: 650px;">
                <div><dd>Title:</dd> <?php echo $movie->title; ?></div>
                <div><dd>Original Title:</dd> <?php echo $movie->original_title; ?></div>
                <div><dd>Date:</dd> <?php echo date('jS \of F Y', strtotime($movie->release_date)); ?></div>
                <div><dd>Rating:</dd> <?php echo $movie->vote_average; ?></div>
                <div><dd>Runtime:</dd> <?php echo $movie->runtime; ?></div>
                <div><dd>Description:</dd><dt><?php echo $movie->overview; ?></dt></div>
                <div>
                    <dd>Genres:</dd>
                    <dt>
                        <ul>
                        <?php foreach ($movie->genres as $genre) { ?>
                            <li><?php echo $genre->name; ?></li>
                        <?php } ?>
                        </ul>
                    </dt>
                </div>
            </div>
        </div>
<?php
}
?>

