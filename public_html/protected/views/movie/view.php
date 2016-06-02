<?php
/* @var $this MovieController */
/* @var $movie Movie */
?>

<div style="height:50px"><h1 style="width: 50%; float: left;">Movie details</h1></div>

<?php
if (isset($error)) { ?>
	<h2><?php echo $error; ?></h2>
<?php } else if (isset($movie)) { ?>
	<div class="full">
		<div style="float: left;">
			<?php if ($movie->poster_path && is_file(getcwd().$movie->poster_path)) { ?>
				<img src="<?php echo $movie->poster_path; ?>" class="crop" />
			<?php } else { ?>
				<img src="http://placehold.it/185x278/eee/ccc?text=No Image" class="crop" />
			<?php } ?>
		</div>
		<div style="float: left; padding-left: 20px; width: 650px;">
			<div><dd>Title:</dd> <?php echo CHtml::encode($movie->title); ?></div>
			<?php if ($movie->original_title) { ?><div><dd>Original Title:</dd> <?php echo CHtml::encode($movie->original_title); ?></div><?php } ?>
			<?php if ($movie->release_date) { ?><div><dd>Date:</dd> <?php echo date('jS \of F Y', strtotime($movie->release_date)); ?></div><?php } ?>
			<?php if ($movie->vote_average) { ?><div><dd>Rating:</dd> <?php echo $movie->vote_average; ?></div><?php } ?>
			<?php if ($movie->runtime) { ?><div><dd>Runtime:</dd> <?php echo CHtml::encode($movie->runtime); ?></div><?php } ?>
			<?php if ($movie->overview) { ?><div><dd>Description:</dd><dt><?php echo CHtml::encode($movie->overview); ?></dt></div><?php } ?>
			<?php if (count($movie->genres)) { ?>
				<div>
					<dd>Genres:</dd>
					<dt>
					<ul>
						<?php foreach ($movie->genres as $genre) { ?>
							<li><?php echo $genre; ?></li>
						<?php } ?>
					</ul>
					</dt>
				</div>
			<?php } ?>
			<div>
				<dd>My Rate:</dd>
				<dt>
			<?php
			$this->widget('CStarRating',array(
				'name'=>'star_rating_ajax',
				'value'=>$movie->rate,
				'minRating'=>1,
            	'maxRating'=>5,
				'callback'=>'
					function(){
							var val = typeof($(this).attr(\'type\'))==\'undefined\' ? 0 : $(this).val();
							$.ajax({
								type: "POST",
								url: "'.Yii::app()->createUrl('movie/rate').'",
								data: "id='.$movie->id.'&rating=" + val,
								success: function(data){
											$("#mystar_voting").html(data);
									}})}'
						));
						echo "<br/>";
						echo "<div id='mystar_voting'></div>";
			?>
				</dt>
			</div>
			<div class="clearfix" style="height: 32px;"></div>
			<a href="/movie/update/<?php echo $movie->id; ?>">Edit</a>
			<a href="/site/index" onclick="if(confirm('Are you sure?')){$.ajax({'method':'POST','url':'/movie/delete/<?php echo $movie->id; ?>'}); return true}else{return false}">Delete</a>
		</div>
	</div>
<?php } ?>
