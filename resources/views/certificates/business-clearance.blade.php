<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Business Clearance</title>
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
        .doc-title { text-align: center; margin: 25px 0 20px; }
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
        .detail { font-weight: bold; border-bottom: 1px solid #333; padding: 0 3px; }
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
        .business-box {
            background: #f0f4ff;
            border: 1px solid #c7d2fe;
            border-left: 4px solid #1a1a7e;
            padding: 12px 15px;
            margin: 15px 0;
        }
        .business-box table { width: 100%; border-collapse: collapse; }
        .business-box table td { padding: 4px 8px; font-size: 11pt; }
        .business-box table td.label { width: 40%; color: #555; }
        .business-box table td.value { font-weight: bold; }
        .year-badge {
            display: inline-block;
            background: #1a1a7e;
            color: #fff;
            font-size: 11pt;
            font-weight: bold;
            padding: 4px 16px;
            border-radius: 4px;
            margin: 8px 0;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <p class="republic">Republic of the Philippines</p>
        <p class="republic">Province of Negros Oriental &bull; Municipality of La Libertad</p>
        <p class="barangay-name">{{ $barangay }}</p>
        <p class="municipality">{{ $municipality }}, {{ $province }}</p>
        <p class="office">Office of the Barangay Captain</p>
    </div>

    @if($or_number)
    <div class="or-box">OR No.: <strong>{{ $or_number }}</strong></div>
    <div style="clear:both;"></div>
    @endif

    <div class="control-number">Control No.: <strong>{{ $control_number }}</strong></div>

    <div class="doc-title">
        <h1>Barangay Business Clearance</h1>
        <div class="year-badge">{{ now()->year }}</div>
    </div>

    <p class="body-text">TO WHOM IT MAY CONCERN:</p>

    <p class="body-text" style="margin-top: 15px;">
        This is to certify that <span class="name">{{ $resident->full_name }}</span>,
        {{ $resident->age ?? '—' }} years of age, Filipino citizen, residing at
        <span class="detail">{{ $resident->address }}, {{ $barangay }}, {{ $municipality }}, {{ $province }}</span>,
        has applied for a Barangay Business Clearance for the operation of a business establishment
        within the jurisdiction of this barangay.
    </p>

    <!-- Business Details -->
    <div class="business-box">
        <p style="font-weight: bold; margin-bottom: 8px; color: #1a1a7e;">Business Information</p>
        <table>
            <tr>
                <td class="label">Business Owner:</td>
                <td class="value">{{ strtoupper($resident->full_name) }}</td>
            </tr>
            <tr>
                <td class="label">Business Address:</td>
                <td class="value">{{ $resident->address }}, {{ $barangay }}</td>
            </tr>
            <tr>
                <td class="label">Nature of Business:</td>
                <td class="value">{{ $purpose }}</td>
            </tr>
            <tr>
                <td class="label">Year of Operation:</td>
                <td class="value">{{ now()->year }}</td>
            </tr>
        </table>
    </div>

    <p class="body-text">
        This further certifies that the above-named business owner has complied with the requirements
        of this Barangay and that the operation of the said business is hereby cleared and has no
        objection from this office as of this date.
    </p>

    <table class="info-table">
        <tr>
            <td class="label">Owner's Date of Birth:</td>
            <td class="value">{{ $resident->birthdate?->format('F d, Y') ?? '—' }}</td>
            <td class="label">Zone:</td>
            <td class="value">{{ $resident->zone }}</td>
        </tr>
        <tr>
            <td class="label">Contact Number:</td>
            <td class="value">{{ $resident->contact_number ?? '—' }}</td>
            <td class="label">Gender:</td>
            <td class="value">{{ $resident->gender ? ucfirst($resident->gender) : '—' }}</td>
        </tr>
    </table>

    <p class="validity">
        Valid until: <strong>December 31, {{ now()->year }}</strong> &nbsp;|&nbsp;
        Issued on: <strong>{{ $issued_at }}</strong>
        <span class="not-valid">&nbsp; NOT VALID WITHOUT THE OFFICIAL SEAL</span>
    </p>

    <div class="signature-section">
        <div class="signature-block">
            <div class="signature-line"></div>
            <p class="signature-name">{{ $resident->full_name }}</p>
            <p class="signature-title">Signature of Applicant / Business Owner</p>
        </div>
        <div class="signature-block">
            <div class="signature-line"></div>
            <p class="signature-name">{{ $signatory?->name ?? 'BARANGAY CAPTAIN' }}</p>
            <p class="signature-title">Barangay Captain</p>
            <p class="signature-title">{{ $barangay }}, {{ $municipality }}</p>
        </div>
    </div>

    <div class="footer">
        <p>{{ $barangay }} &bull; {{ $municipality }}, {{ $province }} &bull; Document Control No.: {{ $control_number }}</p>
        <p>Official copy from barangay records. Valid only with the official dry seal of the barangay.</p>
    </div>

</body>
</html>