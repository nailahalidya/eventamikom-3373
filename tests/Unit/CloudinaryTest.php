<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Partner;
use App\Models\Organizer;

class CloudinaryTest extends TestCase
{
    /** @test */
    public function event_poster_url_returns_cloudinary_url_when_stored_as_full_url()
    {
        $event = new Event([
            'poster_path' => 'https://res.cloudinary.com/jy0bx3eb/image/upload/v12345/posters/test.jpg'
        ]);

        $this->assertEquals(
            'https://res.cloudinary.com/jy0bx3eb/image/upload/v12345/posters/test.jpg',
            $event->poster_url
        );
    }

    /** @test */
    public function partner_logo_url_returns_full_cloudinary_url()
    {
        $partner = new Partner([
            'logo_url' => 'https://res.cloudinary.com/jy0bx3eb/image/upload/v12345/partners/logo.png'
        ]);

        $this->assertEquals(
            'https://res.cloudinary.com/jy0bx3eb/image/upload/v12345/partners/logo.png',
            $partner->logo_url
        );
    }

    /** @test */
    public function organizer_logo_url_returns_full_cloudinary_url()
    {
        $organizer = new Organizer([
            'name' => 'HIMTI Amikom',
            'logo' => 'https://res.cloudinary.com/jy0bx3eb/image/upload/v12345/organizers/himti.png'
        ]);

        $this->assertEquals(
            'https://res.cloudinary.com/jy0bx3eb/image/upload/v12345/organizers/himti.png',
            $organizer->logo_url
        );
    }
}
