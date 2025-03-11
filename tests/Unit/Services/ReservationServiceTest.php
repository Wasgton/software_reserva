<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ReservationService;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Services\PropertyService;
use App\Models\Property;
use App\Models\Reservation;
use Mockery;
use Carbon\Carbon;

class ReservationServiceTest extends TestCase
{
    protected $reservationRepository;
    protected $propertyService;
    protected $reservationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reservationRepository = Mockery::mock(ReservationRepositoryInterface::class);
        $this->propertyService = Mockery::mock(PropertyService::class);

        $this->reservationService = new ReservationService(
            $this->reservationRepository,
            $this->propertyService
        );
    }

    public function test_it_creates_reservation_with_correct_calculations()
    {
        $property = new Property([
            'id' => 1,
            'daily_rate' => 1000.00
        ]);

        $reservationData = [
            'property_id' => 1,
            'check_in' => '2024-04-01',
            'check_out' => '2024-04-05',
            'cleaning_fee' => 150.00
        ];

        $this->propertyService
            ->shouldReceive('getProperty')
            ->with(1)
            ->andReturn($property);

        $expectedReservation = new Reservation([
            'property_id' => 1,
            'nights' => 4,
            'daily_rate' => 1000.00,
            'total_amount' => 4150.00,
            'cleaning_fee' => 150.00
        ]);

        $this->reservationRepository
            ->shouldReceive('create')
            ->andReturn($expectedReservation);

        $reservation = $this->reservationService->createReservation($reservationData);

        $this->assertEquals(4, $reservation->nights);
        $this->assertEquals(4150.00, $reservation->total_amount);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
