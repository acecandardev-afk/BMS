<?php

namespace App\Console\Commands;

use App\Models\CertificateRequest;
use App\Notifications\CertificateStatusUpdatedNotification;
use Illuminate\Console\Command;

class AdvanceCertificateRequestStatuses extends Command
{
    protected $signature = 'requests:advance-statuses';
    protected $description = 'Advance approved requests to shipped, then on delivery.';

    public function handle(): int
    {
        $now = now();

        $approvedCandidates = CertificateRequest::query()
            ->where('status', CertificateRequest::STATUS_APPROVED)
            ->whereNotNull('approved_at')
            ->whereNull('shipped_at')
            ->get();
        $approvedToShip = $approvedCandidates->filter(function (CertificateRequest $request) use ($now) {
            $delay = $request->ship_delay_minutes ?? 5;
            return $request->approved_at?->copy()->addMinutes($delay)->lessThanOrEqualTo($now);
        });

        foreach ($approvedToShip as $request) {
            $request->update([
                'status' => CertificateRequest::STATUS_SHIPPED,
                'shipped_at' => now(),
            ]);
            $this->notifyOwner($request);
        }

        $shippedCandidates = CertificateRequest::query()
            ->where('status', CertificateRequest::STATUS_SHIPPED)
            ->whereNotNull('shipped_at')
            ->whereNull('on_delivery_at')
            ->get();
        $shippedToDelivery = $shippedCandidates->filter(
            fn (CertificateRequest $request) => $request->shipped_at?->copy()->addHours(8)->lessThanOrEqualTo($now)
        );

        foreach ($shippedToDelivery as $request) {
            $request->update([
                'status' => CertificateRequest::STATUS_ON_DELIVERY,
                'on_delivery_at' => now(),
            ]);
            $this->notifyOwner($request);
        }

        $this->info("Updated {$approvedToShip->count()} to shipped, {$shippedToDelivery->count()} to on delivery.");

        return self::SUCCESS;
    }

    private function notifyOwner(CertificateRequest $request): void
    {
        $recipient = $request->requester ?? $request->resident?->user;
        if ($recipient) {
            $recipient->notify(new CertificateStatusUpdatedNotification($request));
        }
    }
}
