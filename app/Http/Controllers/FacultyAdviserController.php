<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\EventApprovalFlow;
use App\Models\Permit;
use App\Models\Organization;

class FacultyAdviserController extends Controller
{
  /**
   * Adviser dashboard showing permit counts.
   */
  public function dashboard()
  {
    $adviserId = Auth::user()->user_id;

    $organizationIds = Organization::where('adviser_id', $adviserId)
      ->pluck('organization_id')
      ->toArray();

    $pendingReviews = $approved = $rejected = 0;

    if (!empty($organizationIds)) {
      $permitIds = Permit::whereIn('organization_id', $organizationIds)
        ->pluck('permit_id')
        ->toArray();

      $pendingReviews = EventApprovalFlow::where('approver_role', 'Faculty_Adviser')
        ->where('status', 'pending')
        ->whereIn('permit_id', $permitIds)
        ->count();

      $approved = EventApprovalFlow::where('approver_role', 'Faculty_Adviser')
        ->where('status', 'approved')
        ->whereIn('permit_id', $permitIds)
        ->count();

      $rejected = EventApprovalFlow::where('approver_role', 'Faculty_Adviser')
        ->where('status', 'rejected')
        ->whereIn('permit_id', $permitIds)
        ->count();
    }

    return view('adviser.dashboard', compact('pendingReviews', 'approved', 'rejected'));
  }

  /**
   * List pending approvals for adviser.
   */
  public function approvals()
  {
    $adviserId = Auth::user()->user_id;

    $organizationIds = Organization::where('adviser_id', $adviserId)
      ->pluck('organization_id')
      ->toArray();

    $permitIds = Permit::whereIn('organization_id', $organizationIds)
      ->pluck('permit_id')
      ->toArray();

    $pendingPermits = EventApprovalFlow::with(['permit.organization'])
      ->where('approver_role', 'Faculty_Adviser')
      ->where('status', 'pending')
      ->whereIn('permit_id', $permitIds)
      ->orderBy('created_at', 'desc')
      ->get();

    return view('adviser.approvals', compact('pendingPermits'));
  }

  /**
   * View PDF of a permit by hashed_id.
   */
  public function viewPermitPdf($hashed_id)
  {
    $permit = Permit::where('hashed_id', $hashed_id)->firstOrFail();

    if ($permit->pdf_data) {
      return response($permit->pdf_data)
        ->header('Content-Type', 'application/pdf');
    }

    abort(404, 'PDF not available.');
  }

  /**
   * Approve a permit and insert adviser signature & name.
   */
  public function approve(Request $request, $approval_id)
  {
    $request->validate([
      'password' => 'required',
      'signature_data' => 'nullable',
      'signature_upload' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    // Check password
    if (!Hash::check($request->password, Auth::user()->password)) {
      return back()->with('error', 'Incorrect password. Approval canceled.');
    }

    $flow = EventApprovalFlow::findOrFail($approval_id);
    $permit = Permit::findOrFail($flow->permit_id);

    if (!$permit->pdf_data) {
      return back()->with('error', 'PDF is not generated yet.');
    }

    // Handle signature storage
    if ($request->signature_upload) {
      $flow->signature_path = $request->file('signature_upload')->store('signatures', 'public');
    } elseif ($request->signature_data) {
      $image = str_replace('data:image/png;base64,', '', $request->signature_data);
      $image = base64_decode($image);
      $fileName = 'signatures/' . uniqid() . '.png';
      Storage::disk('public')->put($fileName, $image);
      $flow->signature_path = $fileName;
    }

    // Update approval flow
    $flow->status = 'approved';
    $flow->approver_id = Auth::user()->user_id;
    $flow->approved_at = now();
    $flow->save();

    // Insert signature & name into PDF via PDF.co
    $pdfBase64 = base64_encode($permit->pdf_data);
    $signatureBase64 = null;

    if ($flow->signature_path && file_exists(storage_path('app/public/' . $flow->signature_path))) {
      $signatureBase64 = base64_encode(file_get_contents(storage_path('app/public/' . $flow->signature_path)));
    }

    $adviserName = strtoupper(Auth::user()->name);

    $payload = [
      "async" => false,
      "profiles" => "optimize",
      "url" => "data:application/pdf;base64,$pdfBase64",
      "images" => $signatureBase64 ? [
        [
          "x" => 153,       // Adjust X for your template
          "y" => 207,       // Adjust Y for your template
          "width" => 40,    // Signature width
          "height" => 20,   // Signature height
          "image" => "data:image/png;base64,$signatureBase64",
          "pages" => "1"
        ]
      ] : [],
      "text" => [
        [
          "text" => $adviserName,
          "x" => 153,         // Same X as signature
          "y" => 203,         // Slightly above signature
          "size" => 10,
          "pages" => "1"
        ]
      ]
    ];

    $response = Http::withHeaders([
      "x-api-key" => config('services.pdfco.key')
    ])->post("https://api.pdf.co/v1/pdf/edit/add", $payload);

    if (!$response->successful() || !$response->json('body')) {
      return back()->with('error', 'PDF.co failed: ' . $response->json('message'));
    }

    $updatedPdfBase64 = $response->json('body');
    $permit->pdf_data = base64_decode($updatedPdfBase64);
    $permit->save();

    return back()->with('success', 'Permit approved and PDF updated.');
  }

  /**
   * Reject a permit.
   */
  public function reject(Request $request, $approval_id)
  {
    $request->validate(['comments' => 'required|string']);

    $flow = EventApprovalFlow::findOrFail($approval_id);
    $flow->update([
      'status' => 'rejected',
      'comments' => $request->comments,
      'approver_id' => Auth::user()->user_id,
      'approved_at' => now(),
    ]);

    return back()->with('error', 'Permit rejected.');
  }
}
