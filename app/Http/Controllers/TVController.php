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
        /*Validation for uploaded file. It should be json/xml and csv*/
        $validator = Validator::make($request->all(), [
                'inputFile' => 'mimes:json,xml,csv',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        /*Get extension of uploaded file. Depending on what kind of extension,
        it will be formatted to json pattern*/
        $extension = $this->getExtension($request->inputFile->getClientMimeType());
        $string = file_get_contents($request->inputFile);
        $json = $this->formatToJson($extension, $string);
        
        /*The final json file will be executed to import to database*/
        try {
            $this->channelRepository->create($json);
            $this->programRepository->create($json);
            $this->scheduleRepository->create($json);
            
            return view('home', [
                'success' => true,
                'message' => 'Good job'
            ]);
        /*If there are any errors, they will be catched and send the error 
         * notification to users*/
        } catch (\Exception $ex) {
            return view('home', [
                'success' => false,
                'message' => $ex->getMessage()
            ]);
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
