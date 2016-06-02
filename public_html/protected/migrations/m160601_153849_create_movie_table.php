<?php

class m160601_153849_create_movie_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('tbl_movie', array(
            'id'             => 'pk',
            'tmdbID'         => 'int',
            'title'          => 'string NOT NULL',
            'original_title' => 'string',
            'overview'       => 'text',
            'release_date'   => 'date',
            'runtime'        => 'int',
            'vote_average'   => 'float',
            'poster_path'    => 'text',
            'genres'         => 'text',
            'rate'           => 'int',
        ));
    }

    public function down()
    {
        echo "m160601_153849_create_movie_table does not support migration down.\n";
        return false;
    }

}
