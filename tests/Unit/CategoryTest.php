<?php

namespace Tests\Unit;

use App\Category;
use App\Channel;
use App\Domain;
use App\Item;
use Tests\ModelTestCase;

class CategoryTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Category(), [
            'content'
        ]);
    }

    public function test_categories_relation()
    {
        $model = new Category();
        $relation = $model->domain();
        $this->assertBelongsToRelation($relation, $model, new Domain(), 'domain_id');
    }

    public function test_items_relation()
    {
        $model = new Category();
        $relation = $model->items();
        $this->assertHasManyRelation($relation, $model, new Item());
    }

    public function test_channels_relation()
    {
        $model = new Category();
        $relation = $model->channels();
        $this->assertHasManyRelation($relation, $model, new Channel());
    }
}
