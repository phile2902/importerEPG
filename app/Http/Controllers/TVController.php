<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ChannelRepository;
use App\Repositories\ProgramRepository;
use App\Repositories\ScheduleRepository;
use App\StaticClass\Xml;
use Illuminate\Support\Facades\Validator;

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
    
    public function executeFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'inputFile' => 'mimes:json,xml,csv',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $extension = $this->getExtension($request->inputFile->getClientMimeType());
        $string = file_get_contents($request->inputFile);
        $json = $this->formatToJson($extension, $string);
        
        try {
            $this->channelRepository->create($json);
            $this->programRepository->create($json);
            $this->scheduleRepository->create($json);
            echo "good";
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

    }
    
    public function getExtension($param)
    {
        $array = explode('/',$param);
        return $array[1];
    }
    
    public function formatToJson($extension,$string)
    {
        switch ($extension) {
            case 'xml':
                return Xml::formatXmlToJson($string);
                break;
            case 'json':
                break;
            case 'csv':
                break;
            default:
                break;
        }
    }
}
