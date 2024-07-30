<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Enums\EventStatus;
use Carbon\Carbon;
use App\Models\Events;

class CalanderController extends Controller
{
    public function GenerateEvents()
    {


        $get_events = Events::get();
        $calendar = Calendar::create()
            ->name('Dar Al-Iskan Events Calander')
            ->description('This Calander Shows Events from Dar Al-Iskan Dashboard.')
            ->refreshInterval(120);

        foreach ($get_events as $event) {

            $event = Event::create()
                ->name($event->title)
                ->description($event->description)
                ->status(EventStatus::confirmed())
                ->startsAt(Carbon::parse($event->start_date))
                ->endsAt(Carbon::parse($event->end_date));
        }
        $calendarContent = $calendar->get();
        $filePath = public_path('calendar.ics');
        // $request->file('file')->move($storagePath, $imageName);
        // Save the calendar content to the public folder
        file_put_contents($filePath, $calendarContent);

        // Optionally, you can also return the file path or a download link
        // return response()->download($filePath, 'calendar.ics', ['Content-Type' => 'text/calendar']);
        // Set the Content-Type header explicitly
        return response()->download($filePath, 'calendar.ics', [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'attachment; filename=calendar.ics',
        ]);
    }


    public function CreateEvent(Request $req)
    {
        $req->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        try {
            $event = new Events();
            $event->start_date = $req['start_date'];
            $event->end_date = $req['end_date'];
            $event->title = $req['title'];
            $event->description = $req['description'];
            $event->save();
            return response()->json(['status' => '200']);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500], 500);
        }
    }

    public function UpdateEvent(Request $req)
    {
        $req->validate([
            'id' => 'required',
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        try {
            Events::where('id', $req['id'])->update(['start_date' => $req['start_date'], 'end_date' => $req['end_date'], 'title' => $req['title'], 'description' => $req['description']]);
            return response()->json(['status' => 200]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500], 500);
        }
    }

    public function DeleteEvent(Request $req)
    {
        $req->validate([
            'id' => 'required',
        ]);

        try {
            Events::where('id', $req['id'])->delete();
            return response()->json(['status' => 200]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500], 500);
        }
    }
}
