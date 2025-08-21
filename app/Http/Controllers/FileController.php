<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    // Upload file
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('uploads', 'public');

        $file = Document::create([
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name'   => basename($path),
            'folder'        => 'uploads',
            'mime_type'     => $uploadedFile->getClientMimeType(),
            'size'          => $uploadedFile->getSize()
        ]);

        return response()->json($file, 201);
    }

    // Preview (open in browser)
public function view($id)
{
    $file = Document::findOrFail($id);
    $filePath = $file->folder . '/' . $file->stored_name;
    return Storage::disk('public')->response($filePath);
}

// Delete
public function destroy($id)
{
    $file = Document::findOrFail($id);

    // Delete file from storage
    Storage::disk('public')->delete($file->folder . '/' . $file->stored_name);

    // Delete DB record
    $file->delete();

    return response()->json(['message' => 'File deleted successfully']);
}

    // List all files
    public function index()
    {
        $files = Document::orderBy('created_at', 'desc')->get();
        return response()->json($files);
    }

    // Download file
    public function download($id)
    {
        $file = Document::findOrFail($id);
        $filePath = $file->folder . '/' . $file->stored_name;
        return Storage::disk('public')->download($filePath, $file->original_name);
    }
}
