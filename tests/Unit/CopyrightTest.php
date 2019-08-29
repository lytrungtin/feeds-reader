<?php

namespace Tests\Unit;

use App\Copyright;
use App\Channel;
use Tests\ModelTestCase;

class CopyrightTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Copyright(), [
            'copyright'
        ]);
    }
    public function test_channels_relation()
    {
        $model = new Copyright();
        $relation = $model->channels();
        $this->assertHasManyRelation($relation, $model, new Channel());
    }
}
