<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gpoa;
use App\Models\GpoaActivity;
use App\Models\Event;
use App\Models\AccomReport;
use App\Services\Stream;

class HomeController extends Controller
{
    public function index() 
    {
        $gpoaActive = Gpoa::active()->exists();
        $gpoa = Gpoa::active()->first();
	if (!$gpoa) {
            return view('home.user', [
                'gpoaActive' => false,
                'pendingAccomReportCount' => 0,
                'pendingGpoaActivityCount' => 0,
                'upcomingEventCount' => 0,
                'gpoaRoute' => route('gpoa.index'),
                'eventsRoute' => route('events.index'),
                'accomReportsRoute' => route('accom-reports.index'),
            ]);
        }
	//dd(self::featured());
        return view('home.user', [
            'gpoaActive' => true,
            'pendingAccomReportCount' => AccomReport::active()
                ->where('status', 'pending')->count(),
            'pendingGpoaActivityCount' => $gpoa->activities()
                ->where('status', 'pending')->count(),
            'upcomingEventCount' => Event::active()->upcoming()->count(),
            'gpoaRoute' => route('gpoa.index'),
            'eventsRoute' => route('events.index'),
            'accomReportsRoute' => route('accom-reports.index'),
        ] + self::featured());
    }

    public function adminIndex() 
    {
        return view('home.admin');
    }

    public function stream()
    {
        return Stream::event('home_stream');
    }

    private static function featured(): array
    {
        $activityCount = GpoaActivity::active()->count();
        $eventCount = Event::active()->count();
        $status = 'none';
        $contents = [];
        if ($activityCount >= 3 && $eventCount >= 2) {
            $status = 'full';
            $candidates = [
                [
                    'names' => ['ongoing_event', 'upcoming_event', 
                        'recent_event'],
                     'next_link' => '#featured-2',
                     'prev_link' => '#featured-1',
                ],
                [
                    'names' => ['upcoming_event', 'recent_event', 
                        'ongoing_event'],
                     'next_link' => '#featured-3',
                     'prev_link' => '#featured-1',
                ],
                [
                    'names' => ['recent_event', 'ongoing_event', 
                        'upcoming_event', 'new_activity'],
                     'next_link' => '#featured-1',
                     'prev_link' => '#featured-2',
                ]
            ];
            foreach ($candidates as $candidate) {
                foreach ($candidate['names'] as $record) {
                    if (self::getRecord($record, true)) {
                        $contents[] = self::getRecord($record) + [
                            'next_link' => $candidate['next_link'],
                            'prev_link' => $candidate['prev_link']
                        ];
                        break;;
                    }
                }
            }
        } elseif ($activityCount >= 2 && $eventCount >= 1) {
            $status = 'partial';
            $candidates = [
                [
                    'names' => ['ongoing_event', 'upcoming_event', 
                        'recent_event'],
                     'next_link' => '#featured-3',
                     'prev_link' => '#featured-2',
                ],
                [
                    'names' => ['upcoming_event', 'recent_event', 
                        'ongoing_event', 'new_activity'],
                     'next_link' => '#featured-1',
                     'prev_link' => '#featured-3',
                ],
            ];
            foreach ($candidates as $candidate) {
                foreach ($candidate['names'] as $record) {
                    if (self::getRecord($record, true)) {
                        $contents[] = self::getRecord($record) + [
                            'next_link' => $candidate['next_link'],
                            'prev_link' => $candidate['prev_link']
                        ];
                        break;;
                    }
                }
            }
        } 
        return [
            'featStatus' => $status,
            'featContents' => $contents
        ];
    }

    private static function getRecord(string $record, 
        bool $checkOnly = false)
    {
        switch ($record) {
        case 'ongoing_event':
            $model = Event::active()->ongoing();
            $view = 'home-feat-ongoing-event';
            break;
        case 'upcoming_event':
            $model = Event::active()->upcoming();
            $view = 'home-feat-event';
            break;
        case 'recent_event':
            $model = Event::active()->completed();
            $view = 'home-feat-completed-event';
            break;
        case 'new_activity':
            $model = GpoaActivity::active()->orderBy('created_at', 'desc');
            $view = 'home-feat-activity';
            break;
        }
        return $checkOnly ? $model->exists() : [
            'model' => $model->first(),
            'view' => $view
        ];
    }
}
