<?php

namespace App\Http\Controllers;

use App\Models\Uploader;
use App\Service\FileService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Google\Cloud\Storage\StorageClient;
use Google\Service\Analytics\Upload;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

use Illuminate\Support\Facades\Storage;

class FileUploader extends Controller
{
    protected $baseurl='';

    public function __construct(public FileService $fileservice){
         // get the access token and set the access token to config
        config(['filesystems.disks.google.accessToken'=>$this->fileservice->accessToken()]);
    }

    public function getfileUploader(){
        // $this->fileservice->accessToken();
        $files=Uploader::all();

        return view('uploader',compact('files'));
    }

    public function postfileUploader(Request $request){
         $request->validate([
            'zipfile' => 'required|mimes:zip'
        ]);
        // get the access token and set the access token to config
        // config(['filesystems.disks.google.accessToken'=>$this->fileservice->accessToken()]);
        $zip = $request->file('zipfile');
        $zipName = time() . '_' . $zip->getClientOriginalName();
        Gdrive::put($zipName,$zip);
        $contents=Gdrive::all('/');
        $uploadContent=$contents->firstWhere('path',$zipName);
        // dd($uploadContent,$contents);
        if($uploadContent){
            // dd("Hi");
            $fileMeta=$uploadContent->extraMetadata() ?? [] ;
            $this->fileservice->makeFileToPublic($fileMeta['id']);
            Uploader::updateOrCreate(
                ['drive_file_id'=>$fileMeta['id']],
                [
                    'name'=>$fileMeta['name'],
                    'filename'=>$fileMeta['filename'],
                    'extension'=>$fileMeta['extension'],
                    'path'=>$uploadContent->path(),
                    'mime_type'=>$uploadContent->mimeType(),
                    'file_size'=>$uploadContent->filesize(),
                    'visibility'=>$uploadContent->visibility(),
                    'last_modified'=>Carbon::createFromTimestamp($uploadContent->lastModified()),
                ]
            );
        }
      
        
        return back()->with('success', 'Folder uploaded successfully!');
        }
    
    public function downloadFile(string $id){
        $driveFile=Uploader::findOrFail($id);
        $data = Gdrive::get($driveFile->path);
        return response($data->file, 200)
            ->header('Content-Type', $data->ext)
            ->header('Content-disposition', 'attachment; filename="'.$data->filename.'"');
    }

    public function deleteFile(string $id){
        $driveFile=Uploader::findOrFail($id);
        Gdrive::delete($driveFile->path);
        $driveFile->delete();
        return back()->with('success', 'File deleted successfully!');        
    }

}
