<?php

namespace Tests\Unit;

use App\Channel;
use App\Language;
use Tests\ModelTestCase;

class LanguageTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Language(), [
            'code', 'language'

        ]);
    }
    public function test_channels_relation()
    {
        $model = new Language();
        $relation = $model->channels();
        $this->assertHasManyRelation($relation, $model, new Channel());
    }
}
