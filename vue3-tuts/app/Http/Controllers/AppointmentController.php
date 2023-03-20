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

    public function store()
    {
        request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        
        $appointment = Appointment::create([
            'title' => request('title'),
            'client_id' => 1,
            'start_time' => now(),
            'end_time' => now(),
            'description' => request('description'),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        return new AppointmentResource($appointment);
    }

    public function getStatusWithCount() 
    {
        $cases = AppointmentStatus::cases();

        return collect($cases)->map(function ($status) {
            return [
                'name' => $status->name,
                'value' => $status->value,
                'count' => Appointment::where('status', $status->value)->count(),
                'color' => AppointmentStatus::from($status->value)->color(),
            ];
        });
    }
}
