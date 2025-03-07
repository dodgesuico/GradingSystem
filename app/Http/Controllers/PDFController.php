<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use TCPDF;

class CustomPDF extends TCPDF
{
    public function Header()
    {
        // Leave empty to remove the default header (including the black line)
    }
}

class PDFController extends Controller
{
    public function generatePDF(Request $request)
    {
        $studentID = $request->query('studentID');

        // Fetch user and grades
        $user = User::where('studentID', $studentID)->with('grades')->first();

        if (!$user || !$user->grades || $user->grades->isEmpty()) {
            return redirect()->back()->with('error', 'No grades found for this student.');
        }

        // Create PDF instance
        $pdf = new CustomPDF(); // Use the custom class instead of TCPDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('CKCM Grading System');
        $pdf->SetTitle('Student Grades');
        $pdf->SetSubject('User Grades');
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 10);
        // Header (School Information)
        $pdf->Image(public_path('system_images/logo.jpg'), 30, 12, 25); // Adjust logo placement
        $pdf->SetFont('helvetica', 'B', 11); // 'B' makes it bold
        $pdf->Cell(0, 5, "CHRIST THE KING COLLEGE DE MARANDING, INC.", 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 9); // Reset back to normal weight after bold text
        $pdf->Cell(0, 5, "Maranding, Lala, Lanao del Norte", 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 3, "Contact#: Administrator's Office (063)388-7039, Finance Office", 0, 1, 'C');
        $pdf->Cell(0, 3, "(063)388-7282, Registrar Tel. Fax #:(063)388-7373", 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 3, "9211 PHILIPPINES", 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 10, "OFFICE OF THE COLLEGE REGISTRAR", 0, 1, 'C');

        $pdf->Ln(-2); // Moves the cursor UP by 3 units, reducing space

        $pdf->SetLineWidth(0.5);
        $pdf->Line(30, $pdf->GetY(), 180, $pdf->GetY()); // Draw line closer to the text

        $pdf->Ln(2);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, "EVALUATION COPY", 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Ln(3);

        // Student Info
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(100, 5, "NAME: " . strtoupper($user->name), 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(50, 5, "Sex: TBD", 0, 0, 'L');  // Adjust gender if available
        $pdf->Cell(50, 5, "ID No: " . $user->studentID, 0, 1, 'L');
        $pdf->Cell(100, 5, "Course: Bachelor of Science in Computer Science", 0, 1, 'L'); // Adjust course dynamically if needed
        $pdf->Ln(2);

        // Table Header
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(30, 7, "Subjects & Numbers", 1, 0, 'C', true);
        $pdf->Cell(70, 7, "Descriptive Titles", 1, 0, 'C', true);
        $pdf->Cell(15, 7, "Final", 1, 0, 'C', true);
        $pdf->Cell(15, 7, "Credit", 1, 1, 'C', true);

        // Table Body
        foreach ($user->grades as $grade) {
            $pdf->Cell(30, 7, $grade->subject_code, 1);
            $pdf->Cell(70, 7, $grade->descriptive_title, 1);

            $pdf->Cell(15, 7, $grade->final, 1, 0, 'C');

            $pdf->Cell(15, 7, $grade->units, 1, 1, 'C'); // Assuming Credit is the same as Units
        }

        $pdf->Ln(5);

        // Footer
        $pdf->Cell(0, 5, "NOT VALID WITHOUT THE COLLEGE SEAL", 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 5, "__________________________", 0, 1, 'R');
        $pdf->Cell(0, 5, "ELVIN P. SALMERON, MM-EM", 0, 1, 'R');
        $pdf->Cell(0, 5, "Registrar", 0, 1, 'R');

        // Output PDF
        $pdf->Output('grades.pdf', 'I');
    }
}
