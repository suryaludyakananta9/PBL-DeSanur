<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Administrator DeSanur',
            'email' => 'admin@desanur.id',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $traveler = User::factory()->create([
            'name' => 'Wisatawan Sanur',
            'email' => 'wisatawan@desanur.id',
            'password' => 'password',
            'role' => 'traveler',
        ]);

        $categoryData = [
            ['Pantai', 'Destinasi pantai dan tepi laut Sanur'],
            ['Budaya', 'Museum, pura, dan warisan budaya Bali'],
            ['Kuliner', 'Kuliner tradisional dan tempat makan lokal'],
            ['Aktivitas', 'Olahraga, bersepeda, dan pengalaman wisata'],
            ['Akomodasi', 'Tempat menginap di kawasan Sanur'],
        ];
        $categories = collect($categoryData)->map(fn($item, $index) => \App\Models\Category::create([
            'name' => $item[0], 'slug' => \Illuminate\Support\Str::slug($item[0]), 'description' => $item[1],
        ]));

        $facilities = collect(['Tempat Parkir','Toilet','Area Bersantai','Wi-Fi','Akses Difabel'])->map(fn($name) => \App\Models\Facility::create(['name'=>$name,'description'=>'Fasilitas '.$name.' untuk pengunjung']));

        $names = [
            ['Pantai Sanur','pantai'],['Pantai Matahari Terbit','pantai'],['Pantai Karang','pantai'],['Museum Le Mayeur','budaya'],
            ['Pura Segara','budaya'],['Pura Blanjong','budaya'],['Sanur Village Festival Area','aktivitas'],['Jalan Danau Tamblingan','aktivitas'],
            ['Pantai Sindhu','pantai'],['Taman Inspirasi Mertasari','aktivitas'],['Pasar Tradisional Sindhu','kuliner'],['Warung Sate Mak Beng','kuliner'],
            ['Cafe Sanur','kuliner'],['Sanur Night Market','kuliner'],['Mangrove Blanjong','aktivitas'],['Bicycle Track Sanur','aktivitas'],
            ['Sanur Marina Beach','pantai'],['Mertasari Beach','pantai'],['Pantai Semawang','pantai'],['Bali Beach Golf Course','aktivitas'],
            ['Segara Ayu Village','budaya'],['Sanur Art Market','aktivitas'],['Pura Prama Santhi','budaya'],['Taman Kota Sanur','aktivitas'],
            ['Sunrise Point Sanur','pantai'],['Pantai Sanur Timur','pantai'],['Pantai Sanur Barat','pantai'],['Sanur Waterfront','aktivitas'],
            ['Museum Bali Sanur','budaya'],['Traditional Dance Venue','budaya'],['Warung Betutu','kuliner'],['Warung Sate Lilit','kuliner'],
            ['Coffee Corner Sanur','kuliner'],['Seafood Sanur','kuliner'],['Jogging Track Sanur','aktivitas'],['Yoga Lawn Sanur','aktivitas'],
            ['Sanur Eco Park','aktivitas'],['Batu Jimbar Cafe Area','kuliner'],['Community Art Hall','budaya'],['Sanur Beach Walk','pantai'],
            ['Sanur Fisherman Harbor','aktivitas'],['Sanur Heritage Street','budaya'],['Sanur Sunset Garden','aktivitas'],['Sanur Family Beach','pantai'],
            ['Sanur Kite Spot','aktivitas'],['Sanur Water Sport','aktivitas'],['Sanur Cultural Center','budaya'],
        ];

        foreach ($names as $index => $item) {
            $category = match($item[1]) { 'pantai'=>$categories[0], 'budaya'=>$categories[1], 'kuliner'=>$categories[2], default=>$categories[3] };
            $price = $item[1] === 'pantai' || $item[1] === 'aktivitas' ? ($index % 3 === 0 ? 0 : 25000 + ($index % 5) * 15000) : 15000 + ($index % 6) * 20000;
            $destination = \App\Models\Destination::create([
                'category_id'=>$category->id,'name'=>$item[0],'slug'=>\Illuminate\Support\Str::slug($item[0]),
                'description'=>'Nikmati pengalaman autentik di '.$item[0].', salah satu destinasi pilihan kawasan Sanur, Bali.',
                'address'=>'Kawasan '.$item[0].', Sanur, Bali','latitude'=>-8.6753 + ($index % 10)*0.001,'longitude'=>115.2625 + ($index % 10)*0.001,
                'price_min'=>$price,'price_max'=>$price ? $price+50000 : 0,'opening_hours'=>'08.00 - 18.00','estimated_visit_minute'=>60+(($index%4)*30),
                'status'=>'published','source_url'=>null,'verified_at'=>now(),
            ]);
            \App\Models\DestinationImage::create(['destination_id'=>$destination->id,'image_path'=>'/images/'.($index%5===0?'hero.jpg':($index%5===1?'pantai-sanur.jpg':($index%5===2?'museum.jpg':($index%5===3?'karang.jpg':'bersih.jpg')))),'alt_text'=>$item[0],'sort_order'=>0]);
            $destination->facilities()->attach($facilities->take(3)->pluck('id'));
        }

        $firstDestination = \App\Models\Destination::first();
        if ($firstDestination) {
            \App\Models\Review::create(['user_id'=>$traveler->id,'destination_id'=>$firstDestination->id,'rating'=>5,'comment'=>'Suasana pantai yang tenang dan sangat nyaman untuk menikmati pagi di Sanur.','status'=>'approved']);
        }
    }
}
