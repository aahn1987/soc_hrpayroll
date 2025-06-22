<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\EmpDocuments;
use Illuminate\Http\Request;
use ZipArchive;
class EmpDocumentsController extends Controller
{
    public function listfiles(Request $requests)
    {
        $fileslist = EmpDocuments::where('soc_reference', $requests->reference)->get();
        return response()->json($fileslist);
    }
    public function createdirectory($directory = [])
    {
        $userDir = trim($directory['employee_reference'], '/');
        $fullPath = "empfiles/{$userDir}";
        if (!Storage::disk('public')->exists($fullPath)) {
            Storage::disk('public')->makeDirectory($fullPath);
        }
    }
    public function addfile(Request $request)
    {
        $file = $request->file('user_file');
        $filename = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());

        $allowedExtensions = ['gif', 'png', 'jpg', 'jpeg', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'ppsx', 'pdf', 'txt', 'zip', 'rar', 'mp4', 'mp3'];

        if (!in_array($extension, $allowedExtensions)) {
            return response()->json([
                'success' => false,
                'message' => "file type is not acceptable."
            ]);
        }

        $dir = 'empfiles/' . $request->user_dir . '/';
        $path = $file->storeAs($dir, $filename, 'public');
        $returnPath = 'empfiles/' . $request->user_dir . '/' . $filename;
        $returnUrl = asset('storage/' . $returnPath);
        $filetype = getFileType($extension);
        $viewer = generateViewerHtml($extension, $returnUrl);
        $icon = getFileIcon($extension);

        $token = 'file-' . date('YmdHis');

        EmpDocuments::create([
            'soc_reference' => $request->soc_reference,
            'file_token' => $token,
            'filename' => $filename,
            'file_type' => $filetype,
            'file_viewer' => $viewer,
            'file_icon' => asset('storage/fileicons/' . $icon . '.png'),
            'file_url' => $returnUrl,
            'file_path' => $returnPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => "File: {$filename} Uploaded Successfully."
        ]);
    }
    public function deletefile(Request $request)
    {
        $fileslist = EmpDocuments::where('file_token', $request->file_token)->first();
        $fileslist->deleted = 1;
        $fileslist->save();
        $path = $fileslist->file_path;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        return response()->json([
            'success' => true,
            'message' => "File: {$fileslist->filename} Deleted Successfully."
        ]);
    }
    public function zipandownload(Request $request)
    {
        $folder = trim($request->input('user_dir'), '/');
        $sourcePath = storage_path("app/public/empfiles/{$folder}");
        $zipName = $folder . '.zip';
        $zipPath = storage_path("app/public/zips/{$zipName}");
        if (!file_exists($sourcePath)) {
            return response()->json([
                'success' => false,
                'message' => "Directory not found."
            ]);
        }
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($sourcePath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($sourcePath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        } else {
            return response()->json([
                'success' => false,
                'message' => "Failed to create ZIP file."
            ]);
        }
        $publicUrl = asset('storage/zips/' . $zipName);

        return response()->json([
            'success' => true,
            'zip_url' => $publicUrl,
            'message' => "ZIP file created successfully."
        ]);
    }

}
