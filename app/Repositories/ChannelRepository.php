<?php
namespace App\Repositories;
use App\Repositories\Repository;
use App\Models\Channel;
use Faker\Generator;

class ChannelRepository extends Repository 
{   
    protected $faker;
    
    public function __construct(\Faker\Generator $faker)
    {
        $this->faker = $faker;
    }

    public function create($object)
    {
        foreach($object->network as $network)
        {
            foreach($network->service as $service)
            {
                $channel = new Channel;
                $channel->uuid = $this->faker->unique()->uuid;
                $channel->short_name = $this->faker->unique()->word;
                $channel->full_name = $this->faker->name;
                $channel->time_zone = $this->faker->timezone;
                $channel->source_id = $service->attributes()->id;
                $channel->save();
            }
        }
        
        return $channel;
    }
}
