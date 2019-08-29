<?php

namespace Tests\Unit;

use App\Comment;
use App\Item;
use Tests\ModelTestCase;

class CommentTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Comment(), [
            'comments'
        ]);
    }
    public function test_items_relation()
    {
        $model = new Comment();
        $relation = $model->items();
        $this->assertHasManyRelation($relation, $model, new Item());
    }
}
