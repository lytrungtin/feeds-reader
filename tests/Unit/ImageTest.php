<?php

namespace Tests\Unit;

use App\Category;
use App\Image;
use App\Channel;
use App\Link;
use Tests\ModelTestCase;

class ImageTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Image(), [
             'url', 'title', 'description', 'width', 'height'
        ]);
    }

    public function test_image_relation()
    {
        $model = new Image();
        $this->assertBelongsToRelation($model->channel(), $model, new Channel(), 'channel_id');
        $this->assertBelongsToRelation($model->link(), $model, new Link(), 'link_id');
    }
}
