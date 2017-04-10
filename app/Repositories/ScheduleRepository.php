<?php
namespace App\Repositories;
use App\Repositories\Repository;
use App\Models\Schedule;
use App\Models\Program;
use App\Models\Channel;

class ScheduleRepository extends Repository 
{   
    protected $object;


    public function create($json)
    {
        $this->object = json_decode($json);
        foreach($this->object->network as $network)
        {
            foreach($network->service as $service)
            {
                $channel = Channel::where("source_id",$service->id)->first();
                foreach ($service->event as $event) 
                {
                    $startTime = str_replace("/","-",$event->start_time);
                    $duration = $event->duration;
                    $endTime = strtotime($startTime) + strtotime($duration) - strtotime('TODAY');
                    foreach ($event->language as $language) 
                    {
                        foreach ($language->short_event as $shortEvent) 
                        {
                            $program = Program::where('long_title',$shortEvent->name)->first();
                            
                            $schedule = new Schedule;
                            $schedule->ext_schedule_id = $event->id;
                            $schedule->start_time = strtotime($startTime);
                            $schedule->end_time = $endTime;
                            $schedule->channel_id = $channel->id;
                            $schedule->program_id = $program->id;
                            $schedule->save();
                        }
                    }
                }
            }
        }
        
        return $schedule;
    }
}


