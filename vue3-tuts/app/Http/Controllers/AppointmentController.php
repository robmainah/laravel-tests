<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(): object
    {
        $appointments = Appointment::with('client:id,first_name,last_name')
            ->when(request('status'), function ($query) {
                return $query->where('status', AppointmentStatus::from(request('status')));
            })
            ->latest()
            ->paginate();
        return AppointmentResource::collection($appointments);
    }
}
