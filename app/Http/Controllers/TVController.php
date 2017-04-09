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

    public function executeFile(Request $request)
    {
        $string = file_get_contents($request->inputFile);
        $xml = simplexml_load_string($string);
        
        try {
            $this->channelRepository->create($xml);
            $this->programRepository->create($xml);
            $this->scheduleRepository->create($xml);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

        echo "Good Job";
    }
}
