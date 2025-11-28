<?php

namespace App\Http\Controllers;

use App\Event;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{

    // public function index(Request $request)
    // {
    //     $query = Event::query();


    //     if ($request->has('search')) {
    //         $query->where('title', 'like', '%' . $request->search . '%')
    //               ->orWhere('description', 'like', '%' . $request->search . '%');
    //     }

   
    //     if ($request->has('date')) {
    //         $query->whereDate('date', $request->date);
    //     }

    //     if ($request->has('location')) {
    //         $query->where('location', 'like', '%' . $request->location . '%');
    //     }

    //     $events = $query->paginate(10);

    //     return response()->json($events);
    // }

    public function index(Request $request)
    {
     
        $cacheKey = 'events_page_' . $request->get('page', 1) . '_' . md5(serialize($request->all()));

        $events = Cache::remember($cacheKey, 30, function () use ($request) {
            $query = Event::query();

      
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
                });
            }

            if ($request->has('date') && $request->date !== '') {
                $query->whereDate('date', $request->date);
            }

            if ($request->has('location') && $request->location !== '') {
                $query->where('location', 'like', '%' . $request->location . '%');
            }

           
            $query->orderBy('date', 'asc');

            $query->with('tickets');

            
            return $query->paginate(10);
        });

        return response()->json($events);
    }

   
    public function show($id)
    {
        $event = Event::with('tickets')->find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return response()->json([
            'event'   => $event,
            'tickets' => $event->tickets
        ]);
    }
}