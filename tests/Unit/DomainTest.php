<?php

namespace Tests\Unit;

use App\Category;
use App\Domain;
use Tests\ModelTestCase;

class DomainTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Domain(), [
            'domain'
        ]);
    }
    public function test_categories_relation()
    {
        $model = new Domain();
        $relation = $model->categories();
        $this->assertHasManyRelation($relation, $model, new Category());
    }
}
