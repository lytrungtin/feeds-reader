<?php

namespace Tests\Unit;

use App\Docs;
use App\Channel;
use Tests\ModelTestCase;

class DocsTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Docs(), [
            'docs'
        ]);
    }
    public function test_channels_relation()
    {
        $model = new Docs();
        $relation = $model->channels();
        $this->assertHasManyRelation($relation, $model, new Channel());
    }
}
