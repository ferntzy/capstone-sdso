<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;

class PermitController extends Controller
{
  public function showForm()
  {
    return view('permit.form');
  }

  public function generate(Request $request)
  {
    // ✅ Validate inputs
    $request->validate([
      'name' => 'required|string|max:255',
      'organization' => 'required|string|max:255',
      'title_activity' => 'nullable|string|max:255',
      'purpose' => 'nullable|string',
      'venue' => 'nullable|string|max:255',
      'date_start' => 'required|date',
      'date_end' => 'nullable|date|after_or_equal:date_start',
      'time_start' => 'required|string',
      'time_end' => 'required|string',
      'number' => 'nullable|integer',
      'type' => 'nullable',
      'nature' => 'nullable',
      'participants' => 'nullable',
      'participants_other' => 'nullable|string',
      'signature_upload' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
      'signature_data' => 'nullable|string'
    ]);

    // 🧩 Handle single checkbox selections gracefully
    $type = is_array($request->type) ? $request->type[0] ?? null : $request->type;
    $nature = is_array($request->nature) ? $request->nature[0] ?? null : $request->nature;
    $participants = is_array($request->participants) ? $request->participants[0] ?? null : $request->participants;

    if ($request->filled('nature_other')) {
      $nature = 'Other: ' . $request->nature_other;
    }

    // ✅ Load template
    $templatePath = public_path('templates/sdso_org_permit.pdf');
    if (!file_exists($templatePath)) {
      return back()->withErrors(['pdf' => 'Permit template file not found.']);
    }

    $pdf = new Fpdi();
    $pdf->AddPage();
    $pdf->setSourceFile($templatePath);
    $tplId = $pdf->importPage(1);
    $pdf->useTemplate($tplId, 0, 0, 210);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->SetTextColor(0, 0, 0);

    // 🖋 Basic Info
    $pdf->SetXY(73.5, 41);
    $pdf->Write(0, $request->name);

    $pdf->SetXY(73.5, 45);
    $pdf->Write(0, $request->organization);

    $pdf->SetXY(73.5, 49);
    $pdf->Write(0, $request->title_activity);

    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetXY(73.5, 51);
    $pdf->MultiCell(140, 5, $request->purpose);
    $pdf->SetFont('Helvetica', '', 10);

    // 🖋 Type of Event (single)
    $pdf->SetFont('ZapfDingbats', '', 12);
    $typePositions = [
      'In-Campus'  => [75, 62],
      'Off-Campus' => [124.3, 62],
    ];
    if ($type && isset($typePositions[$type])) {
      [$x, $y] = $typePositions[$type];
      $pdf->SetXY($x, $y);
      $pdf->Write(0, chr(52));
    }

    // 🖋 Nature (single)
    $naturePositions = [
      'Training/Seminar'   => [75, 70],
      'Conference/Summit'  => [124.3, 70],
      'Culmination'        => [75, 74.3],
      'Socialization'      => [124.3, 74.3],
      'Meeting'            => [75, 78.3],
      'Concert'            => [124.3, 78.3],
      'Exhibit'            => [75, 82.6],
      'Program'            => [124.3, 82.6],
      'Educational Tour'   => [75, 86.9],
      'Clean and Green'    => [124.3, 86.9],
      'Competition'        => [75, 91.2],
      'Other'              => [124.3, 91.2],
    ];

    if ($nature && isset($naturePositions[$nature])) {
      [$x, $y] = $naturePositions[$nature];
      $pdf->SetXY($x, $y);
      $pdf->Write(0, chr(52));
    }

    $pdf->SetFont('Helvetica', '', 12);
    if ($request->filled('nature_other')) {
      $pdf->SetXY(138, 91.2);
      $pdf->Write(0, $request->nature_other);
    }

    // 🗓️ Date Formatting (safe dash)
    $startDate = strtotime($request->date_start);
    $endDate = $request->date_end ? strtotime($request->date_end) : null;

    if ($endDate && $endDate !== $startDate) {
      if (date('m', $startDate) === date('m', $endDate) && date('Y', $startDate) === date('Y', $endDate)) {
        $dateDisplay = date('m/d', $startDate) . '-' . date('d/Y', $endDate);
      } else {
        $dateDisplay = date('m/d/Y', $startDate) . ' - ' . date('m/d/Y', $endDate);
      }
    } else {
      $dateDisplay = date('m/d/Y', $startDate);
    }

    // ⏰ Time Formatting
    $startTime = date("g:i A", strtotime($request->time_start));
    $endTime = date("g:i A", strtotime($request->time_end));
    $timeDisplay = ($startTime === $endTime) ? $startTime : "$startTime - $endTime";

    // 🖋 Venue / Date / Time
    $pdf->SetFont('Helvetica', '', 11);
    $pdf->SetXY(73.5, 95.5);
    $pdf->Write(0, $request->venue);

    $pdf->SetXY(73.5, 99.6);
    $pdf->Write(0, $dateDisplay);

    $pdf->SetXY(142, 99.6);
    $pdf->Write(0, $timeDisplay);

    // 🖋 Participants (single)
    $pdf->SetFont('ZapfDingbats', '', 12);
    $participantPositions = [
      'Members'      => [75, 103.5],
      'Officers'     => [75, 107.7],
      'All Students' => [75, 111.8],
      'Other'        => [75, 116],
    ];

    if ($participants && isset($participantPositions[$participants])) {
      [$x, $y] = $participantPositions[$participants];
      $pdf->SetXY($x, $y);
      $pdf->Write(0, chr(52));
    }

    if ($request->filled('participants_other')) {
      $pdf->SetFont('Helvetica', '', 11);
      $pdf->SetXY(90, 116);
      $pdf->Write(0, $request->participants_other);
    }

    // 🖋 Number of Participants
    $pdf->SetFont('Helvetica', '', 11);
    $pdf->SetXY(142, 110);
    $pdf->Write(0, $request->number);

    // ✅ Signature
    $signaturePath = null;
    if ($request->hasFile('signature_upload')) {
      $signaturePath = $request->file('signature_upload')->getPathName();
    } elseif ($request->filled('signature_data')) {
      $imgData = $request->input('signature_data');
      $img = str_replace('data:image/png;base64,', '', $imgData);
      $img = str_replace(' ', '+', $img);
      $signaturePath = storage_path('app/temp_signature.png');
      file_put_contents($signaturePath, base64_decode($img));
    }

    if ($signaturePath && file_exists($signaturePath)) {
      $pdf->Image($signaturePath, 133, 207, 40, 20);
    }

    $pdf->SetXY(128, 223);
    $pdf->Write(0, strtoupper($request->name));

    // ✅ Output PDF
    return response($pdf->Output('S'))
      ->header('Content-Type', 'application/pdf')
      ->header('Content-Disposition', 'inline; filename="sdso_permit_filled.pdf"');
  }
}
