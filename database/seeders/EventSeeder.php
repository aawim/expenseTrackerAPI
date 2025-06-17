<?php
namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $availability = ['Available', 'Not-Available'];
        $statuses     = ['Pending', 'Rejected', 'Approved'];
        $eventTitles  = [
            'Tech Conference',
            'Yoga Workshop',
            'Music Concert',
            'Startup Meetup',
            'Art Exhibition',
            'Photography Seminar',
            'Coding Bootcamp',
            'Design Thinking Lab',
            'Marketing Summit',
            'AI Symposium',
        ];

        $venues = [
            'Downtown Hall',
            'City Auditorium',
            'Tech Park Arena',
            'Lakeside Convention Center',
            'Main Street Hub',
            'Innovation Tower',
            'West Wing Pavilion',
            'Grand Plaza',
            'Riverside Center',
            'Digital Dome',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $startDate = Carbon::now()->addDays(rand(1, 30));
            $endDate   = (clone $startDate)->addDays(rand(1, 3));

            Event::create([
                'user_id'      => 1, // Ensure these users exist
                'title'        => $eventTitles[array_rand($eventTitles)] . ' ' . rand(2024, 2025),
                'detail'       => 'Details for the event happening on slot ' . $i,
                'slots'        => rand(5, 20),
                'slots_booked' => rand(0, 5),
                'image'        => 'https://via.placeholder.com/150?text=Event+' . $i,
                'availability' => $availability[array_rand($availability)],
                'status'       => $statuses[array_rand($statuses)],
                'from_date'    => $startDate->format('Y-m-d'),
                'to_date'      => $endDate->format('Y-m-d'),
                'vanue'        => $venues[array_rand($venues)],
            ]);
        }

    }
}
