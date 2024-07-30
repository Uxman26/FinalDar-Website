<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Use the full path

class MediaController extends Controller
{
  public function UploadMedia(Request $request)
  {
    $request->validate(['file' => 'required|file|mimes:jpg,jpeg,png,bmp,tiff,mp4,mov,avi|max:20480']);  // Adding file type validation and increasing the file size limit to 20MB

    $file = $request->file('file');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $storagePath = public_path('uploads');  // Better to use public_path() if it's truly public
    $file->move($storagePath, $filename);

    $publicPath = url('uploads/' . $filename);  // Generates a URL to be accessed from the front-end
    return response()->json(['path' => $publicPath], 200);
  }


  public function DeleteMedia(Request $request)
  {
    // Construct the full path to the file
    $filePath = base_path() . '/public/uploads/' . $request['name'];

    if (File::exists($filePath)) {
      File::delete($filePath); // Correct usage of the delete method
      return response()->json(['message' => 'File Deleted']);
    } else {
      return response()->json(['error' => 'File Not Found!'], 404);
    }
  }
}
