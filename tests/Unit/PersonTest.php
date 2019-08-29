<?php

namespace Tests\Unit;

use App\Channel;
use App\Image;
use App\Person;
use App\User;
use Tests\ModelTestCase;

class PersonTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Person(), [
            'email'
        ]);
    }

    public function test_managing_channels_relation()
    {
        $model = new Person();
        $relation = $model->managing_channels();
        $this->assertHasManyRelation($relation, $model, new User(), 'managing_editor_id');
    }

    public function test_web_master_channels_relation()
    {
        $model = new Person();
        $relation = $model->web_master_channels();
        $this->assertHasManyRelation($relation, $model, new User(),'web_master_id');
    }
}
