<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $roomNumbers = [
            101, 102, 103, 104, 105, 106, 107, 108, 109, 110,
            111, 113, 115, 117,
            201, 202, 203, 204, 205, 206, 207, 208, 209, 210,
            211, 212, 213, 214, 215, 216, 217, 218, 219,
            300, 301, 303, 304, 305, 306, 307, 308, 309, 310,
            311, 312, 313, 314, 315,
        ];

        foreach ($roomNumbers as $number) {
            Room::firstOrCreate(
                ['room_number' => (string) $number],
                [
                    'qr_token' => Str::random(64),
                    'is_active' => true,
                ]
            );
        }
    }
}
