<?php

namespace App\Modules\Room\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    protected $endpointUrl = 'https://reservations.travelclick.com/103237?languageid=1';
    
    public function getPrepareUrl(Request $request)
    {
        $endpoint = $this->endpointUrl;
        $dateRange =  $request->get('date_range');
        $endpoint .= '&roomtypeid=' . $request->get('roomtypeid') . '&rooms=' . $request->get("rooms");
        if ($request->has('date_range')) {
            $dates = explode(' - ', $dateRange);
            $endpoint .= '&datein=' . $dates[0] . '&dateout=' . $dates[1];
        }
        return redirect()->to($endpoint);
    }
}
