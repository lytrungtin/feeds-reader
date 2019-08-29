<?php

namespace Tests\Unit;

use App\Generator;
use App\Channel;
use Tests\ModelTestCase;

class GeneratorTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Generator(), [
            'generator'
        ]);
    }
    public function test_channels_relation()
    {
        $model = new Generator();
        $relation = $model->channels();
        $this->assertHasManyRelation($relation, $model, new Channel());
    }
}
