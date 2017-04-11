<?php
namespace App\StaticClass;

class Xml
{
    static function formatAttribute($data,array $array)
    {
        foreach ($data as $key => $value)
        {
            $array[$key] = (string)$value;    
        }
        return $array;
    }
    
    static function formatXmlToJson($string)
    {
        /*The principal is scanning one by one nodes of xml file then move 
         attributes outside like keys.*/
        $xml = simplexml_load_string($string);
        $array = array();
        $array['network'] = array();
        foreach ($xml->network as $network) {
            $temp_network = array();
            $temp_network['service'] = array();
            $temp_network = self::formatAttribute($network->attributes(), $temp_network);
            foreach ($network->service as $service) {
                $temp_service = array();
                $temp_service['event'] = array();
                $temp_service = self::formatAttribute($service->attributes(), $temp_service);
                foreach ($service->event as $event) {
                    $temp_event = array();
                    $temp_event['language'] = array();
                    $temp_event['content'] = array();
                    $temp_event['parental_rating'] = array();
                    $temp_event = self::formatAttribute($event->attributes(), $temp_event);
                    foreach ($event->language as $language) {
                        $temp_language = array();
                        $temp_language['short_event'] = array();
                        $temp_language = self::formatAttribute($language->attributes(), $temp_language);
                        foreach ($language->short_event as $short_event) {
                            $temp_short_event = array();
                            $temp_short_event = self::formatAttribute($short_event->attributes(), $temp_short_event);
                            array_push($temp_language['short_event'], $temp_short_event);
                        }
                        array_push($temp_event['language'], $temp_language);
                    }

                    foreach ($event->content as $content) {
                        $temp_content = array();
                        $temp_content = self::formatAttribute($content->attributes(), $temp_content);
                        array_push($temp_event['content'], $temp_content);
                    }

                    foreach ($event->parental_rating as $parental_rating) {
                        $temp_parental_rating = array();
                        $temp_parental_rating['country'] = array();
                        $temp_parental_rating = self::formatAttribute($parental_rating->attributes(), $temp_parental_rating);
                        foreach ($language->country as $country) {
                            $temp_country = array();
                            $temp_country = self::formatAttribute($country->attributes(), $temp_country);
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
}
