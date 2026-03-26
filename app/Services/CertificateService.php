<?php

namespace App\Services;

use App\Models\CertificateRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CertificateService
{
    public function generate(CertificateRequest $request): \Barryvdh\DomPDF\PDF
    {
        $request->loadMissing(['resident', 'signatory']);

        $view = $this->resolveView($request->certificate_type);

        $data = $this->buildData($request);

        $pdf = Pdf::loadView($view, $data)
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'serif',
            ]);

        return $pdf;
    }

    private function resolveView(string $type): string
    {
        $map = [
            CertificateRequest::TYPE_BARANGAY_CLEARANCE     => 'certificates.barangay-clearance',
            CertificateRequest::TYPE_CERTIFICATE_RESIDENCY  => 'certificates.certificate-of-residency',
            CertificateRequest::TYPE_CERTIFICATE_INDIGENCY  => 'certificates.certificate-of-indigency',
            CertificateRequest::TYPE_BUSINESS_CLEARANCE     => 'certificates.business-clearance',
            CertificateRequest::TYPE_CERTIFICATE_GOOD_MORAL => 'certificates.certificate-of-good-moral',
        ];

        return $map[$type] ?? 'certificates.generic';
    }

    private function buildData(CertificateRequest $request): array
    {
        $resident  = $request->resident;
        $signatory = $request->signatory;

        $barangay     = 'Barangay Cantupa';
        $municipality = 'La Libertad';
        $province     = 'Negros Oriental';

        $issued = Carbon::now();

        return [
            'request'          => $request,
            'resident'         => $resident,
            'signatory'        => $signatory,
            'barangay'         => $barangay,
            'municipality'     => $municipality,
            'province'         => $province,
            'issued_at'        => $issued->format('F d, Y'),
            'issued_on_formal' => sprintf(
                'Issued this %s day of %s %s at the Office of the Punong Barangay, %s, %s, %s, Philippines.',
                $issued->format('jS'),
                $issued->format('F'),
                $issued->format('Y'),
                $barangay,
                $municipality,
                $province
            ),
            'control_number'   => $this->generateControlNumber($request),
            'or_number'        => $request->or_number,
            'purpose'          => $request->purpose,
            'valid_until'      => $issued->copy()->addMonths(6)->format('F d, Y'),
            'gender_words'     => $this->genderWords($resident?->gender),
        ];
    }

    /**
     * @return array{subject: string, object: string, possessive: string}
     */
    private function genderWords(?string $gender): array
    {
        return match (strtolower(trim((string) $gender))) {
            'female', 'f' => [
                'subject'    => 'she',
                'object'     => 'her',
                'possessive' => 'her',
            ],
            'male', 'm' => [
                'subject'    => 'he',
                'object'     => 'him',
                'possessive' => 'his',
            ],
            default => [
                'subject'    => 'they',
                'object'     => 'them',
                'possessive' => 'their',
            ],
        };
    }

    private function generateControlNumber(CertificateRequest $request): string
    {
        return sprintf(
            'BCG-%d-%04d',
            now()->year,
            $request->id
        );
    }
}