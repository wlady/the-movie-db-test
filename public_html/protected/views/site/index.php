<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php if (Yii::app()->request->url=='/site/current') { ?>
    <div style="height:50px"><h1 style="width: 50%; float: left;">Now Playing Movies</h1>
        <span style="float: right"><a href="/site/index">The most popular movies</a> </span>
    </div>
<?php } else { ?>
    <div style="height:50px"><h1 style="width: 50%; float: left;">The most popular movies</h1>
        <span style="float: right"><a href="/site/current">Now Playing Movies</a> </span>
    </div>
<?php } ?>
<?php
if (isset($error)) {
?>
    <h2><?php echo $error; ?></h2>
<?php
} else if (isset($movies)) {
    foreach ($movies as $movie) {
?>
        <div class="brief">
            <div>
            <?php if ($movie->poster_path) { ?>
            <a href="/movie/view/<?php echo $movie->id; ?>"><img src="<?php echo $configurations->images->base_url; ?>/w185/<?php echo $movie->poster_path; ?>" class="crop" /></a>
            <?php } else { ?>
            <img src="http://placehold.it/185x278/eee/ccc?text=No Image" class="crop" />
            <?php } ?>
            </div>
            <div><h2><?php echo $movie->title; ?></h2></div>
            <div><dd>Date:</dd> <?php echo date('jS \of F Y', strtotime($movie->release_date)); ?></div>
            <div><dd>Rating:</dd> <?php echo $movie->vote_average; ?></div>
        </div>
<?php
    }
?>
    <div class="clearfix"></div>
    <div class="pager">
<?php
    $this->widget('CLinkPager', array(
        'pages' => $pagination,
    ));
}
?>
    </div>
<?php



?>