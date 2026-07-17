<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmokeTest extends TestCase
{
    /**
     * The default scaffold test hit "/", but HomeController queries the
     * product/category tables, and every other route now does too via the
     * header view composer (categories + cart) shared across the whole
     * client layout. This project's migrations alter the existing
     * production MySQL schema in place rather than creating it fresh, so
     * there's nothing for a blank SQLite test DB to query — Laravel's
     * built-in health-check route is the only endpoint left that touches
     * neither a view nor the database.
     */
    public function test_the_application_boots_and_serves_a_db_free_route(): void
    {
        $response = $this->get('/up');

        $response->assertStatus(200);
    }
}
