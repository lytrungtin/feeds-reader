<?php

namespace Tests\Unit;

use App\Channel;
use App\Image;
use App\Link;
use Tests\ModelTestCase;

class LinkTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Channel(), [
            'title', 'description', 'last_build_date', 'publish_date'
        ]);
    }

    public function test_channels_relation()
    {
        $model = new Link();
        $relation = $model->channels();
        $this->assertHasManyRelation($relation, $model, new Channel());
    }

    public function test_images_relation()
    {
        $model = new Link();
        $relation = $model->images();
        $this->assertHasManyRelation($relation, $model, new Image());
    }
}
