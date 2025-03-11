<?php

namespace App\Jobs;

use App\Models\Owner;
use App\Services\ReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateOwnerReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $owner;
    protected $dateRange;

    public function __construct(Owner $owner, array $dateRange)
    {
        $this->owner = $owner;
        $this->dateRange = $dateRange;
    }

    public function handle(ReportService $reportService)
    {
        $report = $reportService->generateOwnerReport($this->owner, $this->dateRange);

        // Aqui você pode salvar o relatório em storage e notificar o usuário
        // quando estiver pronto
    }
}
