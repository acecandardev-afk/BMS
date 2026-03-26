<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate of Residency</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #1a1a1a;
            padding: 40px 60px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #1a1a7e;
            padding-bottom: 15px;
        }
        .header .republic { font-size: 9pt; letter-spacing: 1px; text-transform: uppercase; }
        .header .barangay-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1a1a7e;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header .municipality { font-size: 11pt; color: #333; }
        .header .office { font-size: 10pt; font-style: italic; color: #555; margin-top: 4px; }
        .doc-title {
            text-align: center;
            margin: 25px 0 20px;
        }
        .doc-title h1 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #1a1a7e;
            border-bottom: 2px solid #1a1a7e;
            display: inline-block;
            padding-bottom: 4px;
        }
        .control-number { text-align: right; font-size: 9pt; color: #555; margin-bottom: 15px; }
        .body-text { text-align: justify; line-height: 1.8; margin-bottom: 15px; }
        .name {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13pt;
            border-bottom: 1px solid #333;
            padding: 0 5px;
        }
        .detail {
            font-weight: bold;
            border-bottom: 1px solid #333;
            padding: 0 3px;
        }
        .purpose-box {
            background: #f5f5f5;
            border-left: 4px solid #1a1a7e;
            padding: 10px 15px;
            margin: 15px 0;
            font-style: italic;
        }
        .validity { font-size: 10pt; color: #555; margin: 10px 0 20px; }
        .not-valid { color: #cc0000; font-weight: bold; }
        .signature-section { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature-block { text-align: center; width: 45%; }
        .signature-line { border-top: 1px solid #333; margin-bottom: 5px; margin-top: 40px; }
        .signature-name { font-weight: bold; text-transform: uppercase; font-size: 11pt; }
        .signature-title { font-size: 9pt; color: #555; font-style: italic; }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 8pt;
            color: #888;
            text-align: center;
        }
        table.info-table { width: 100%; margin: 15px 0; border-collapse: collapse; }
        table.info-table td { padding: 4px 8px; font-size: 11pt; }
        table.info-table td.label { width: 35%; color: #555; }
        table.info-table td.value { font-weight: bold; border-bottom: 1px solid #ccc; }
        .or-box {
            float: right;
            border: 1px solid #ccc;
            padding: 6px 12px;
            font-size: 9pt;
            color: #555;
            margin-bottom: 10px;
        }
        .issued-formal { margin-top: 12px; margin-bottom: 8px; }
        .nb-seal { font-size: 8pt; color: #555; margin-top: 24px; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <p class="republic">Republic of the Philippines</p>
        <p class="republic">Province of Negros Oriental &bull; Municipality of La Libertad</p>
        <p class="barangay-name">{{ $barangay }}</p>
        <p class="municipality">{{ $municipality }}, {{ $province }}</p>
        <p class="office">Office of the Punong Barangay</p>
    </div>

    @if($or_number)
    <div class="or-box">OR No.: <strong>{{ $or_number }}</strong></div>
    <div style="clear:both;"></div>
    @endif

    <div class="control-number">Control No.: <strong>{{ $control_number }}</strong></div>

    <div class="doc-title">
        <h1>Certificate of Residency</h1>
    </div>

    <p class="body-text">TO WHOM IT MAY CONCERN:</p>

    <p class="body-text" style="margin-top: 15px;">
        This is to certify that <span class="name">{{ $resident->full_name }}</span>,
        @if($resident->age !== null)
            {{ $resident->age }} years of age,
        @else
            of legal age,
        @endif
        <span class="detail">{{ $resident->gender ? ucfirst($resident->gender) : '—' }}</span>,
        <span class="detail">{{ $resident->civil_status ? ucfirst($resident->civil_status) : '—' }}</span>,
        <span class="detail">{{ $resident->nationality ?? 'Filipino' }}</span>,
        is a bonafide and registered resident of
        <span class="detail">{{ $resident->address ?? '—' }}, {{ $barangay }}, {{ $municipality }}, {{ $province }}</span>.
    </p>

    <p class="body-text">
        This further certifies that the above-named person has been residing in the aforementioned address
        and is known to the Barangay Officials of this barangay.
    </p>

    <table class="info-table">
        <tr>
            <td class="label">Date of Birth:</td>
            <td class="value">{{ $resident->birthdate?->format('F d, Y') ?? '—' }}</td>
            <td class="label">Zone:</td>
            <td class="value">{{ $resident->zone ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Place of Birth:</td>
            <td class="value">{{ $resident->birthplace ?? '—' }}</td>
            <td class="label">Gender:</td>
            <td class="value">{{ $resident->gender ? ucfirst($resident->gender) : '—' }}</td>
        </tr>
        <tr>
            <td class="label">Nationality:</td>
            <td class="value">{{ $resident->nationality ?? 'Filipino' }}</td>
            <td class="label">Civil Status:</td>
            <td class="value">{{ $resident->civil_status ? ucfirst($resident->civil_status) : '—' }}</td>
        </tr>
    </table>

    <div class="purpose-box">
        <strong>Purpose:</strong> {{ $purpose }}
    </div>

    <p class="body-text">
        This certification is being issued upon the request of the above-named person for whatever legal
        purpose it may serve {{ $gender_words['object'] }} best.
    </p>

    <p class="body-text issued-formal">{{ $issued_on_formal }}</p>

    <p class="validity">
        Valid until: <strong>{{ $valid_until }}</strong>
        <span class="not-valid">&nbsp; NOT VALID WITHOUT THE OFFICIAL SEAL</span>
    </p>

    <div class="signature-section">
        <div class="signature-block">
            <div class="signature-line"></div>
            <p class="signature-name">{{ $resident->full_name }}</p>
            <p class="signature-title">Signature of Applicant</p>
        </div>
        <div class="signature-block">
            <div class="signature-line"></div>
            <p class="signature-name">{{ $signatory?->name ?? 'PUNONG BARANGAY' }}</p>
            <p class="signature-title">Punong Barangay</p>
            <p class="signature-title">{{ $barangay }}, {{ $municipality }}</p>
        </div>
    </div>

    <p class="nb-seal">NB: No seal available</p>

    <div class="footer">
        <p>{{ $barangay }} &bull; {{ $municipality }}, {{ $province }} &bull; Document Control No.: {{ $control_number }}</p>
        <p>Official copy from barangay records. Valid only with the official dry seal of the barangay.</p>
    </div>

</body>
</html>