<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ChannelRepository;
use App\Repositories\ProgramRepository;
use App\Repositories\ScheduleRepository;

class TVController extends Controller
{
    protected $channelRepository;
    protected $programRepository;
    protected $scheduleRepository;
    
    public function __construct(ChannelRepository $channelRepository,
        ProgramRepository $programRepository,
        ScheduleRepository $scheduleRepository)
    {
        $this->channelRepository = $channelRepository;
        $this->programRepository = $programRepository;
        $this->scheduleRepository = $scheduleRepository;
    }
    
    public function formatAttribute($data,array $array)
    {
        foreach ($data as $key => $value)
        {
            $array[$key] = (string)$value;    
        }
        return $array;
    }
    
    public function formatXmlToJson($string)
    {
        $xml = simplexml_load_string($string);
        $array = array();
        $array['network'] = array();
        //var_dump($xml);
        foreach ($xml->network as $network) {
            $temp_network = array();
            $temp_network['service'] = array();
            $temp_network = $this->formatAttribute($network->attributes(), $temp_network);
            foreach ($network->service as $service) {
                $temp_service = array();
                $temp_service['event'] = array();
                $temp_service = $this->formatAttribute($service->attributes(), $temp_service);
                foreach ($service->event as $event) {
                    $temp_event = array();
                    $temp_event['language'] = array();
                    $temp_event['content'] = array();
                    $temp_event['parental_rating'] = array();
                    $temp_event = $this->formatAttribute($event->attributes(), $temp_event);
                    foreach ($event->language as $language) {
                        $temp_language = array();
                        $temp_language['short_event'] = array();
                        $temp_language = $this->formatAttribute($language->attributes(), $temp_language);
                        foreach ($language->short_event as $short_event) {
                            $temp_short_event = array();
                            $temp_short_event = $this->formatAttribute($short_event->attributes(), $temp_short_event);
                            array_push($temp_language['short_event'], $temp_short_event);
                        }
                        array_push($temp_event['language'], $temp_language);
                    }

                    foreach ($event->content as $content) {
                        $temp_content = array();
                        $temp_content = $this->formatAttribute($content->attributes(), $temp_content);
                        array_push($temp_event['content'], $temp_content);
                    }

                    foreach ($event->parental_rating as $parental_rating) {
                        $temp_parental_rating = array();
                        $temp_parental_rating['country'] = array();
                        $temp_parental_rating = $this->formatAttribute($parental_rating->attributes(), $temp_parental_rating);
                        foreach ($language->country as $country) {
                            $temp_country = array();
                            $temp_country = $this->formatAttribute($country->attributes(), $temp_country);
                            array_push($temp_language['country'], $temp_country);
                        }
                        array_push($temp_event['parental_rating'], $temp_parental_rating);
                    }
                    array_push($temp_service['event'], $temp_event);
                }
                array_push($temp_network['service'], $temp_service);
            }
            array_push($array['network'], $temp_network);
        }
        return json_encode($array);
    }

    public function executeFile(Request $request)
    {
        $string = file_get_contents($request->inputFile);
        $json = $this->formatXmlToJson($string);
      
        try {
            $this->channelRepository->create($json);
            $this->programRepository->create($json);
            $this->scheduleRepository->create($json);
            echo "good";
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

        //echo "Good Job";
    }
}
