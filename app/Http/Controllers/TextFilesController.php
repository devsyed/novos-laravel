<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; 
use Illuminate\Support\Facades\Validator;
use App\Helpers\NovosResponseFormatter;
use App\Helpers\NovosHelpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;
use App\Models\File;

class TextFilesController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $filesystemFiles = Storage::files('novos-text-files');
        $databaseFiles = File::all();

        $mergedFiles = $databaseFiles->map(function ($databaseFile) use ($filesystemFiles) {
            $existsInFilesystem = in_array('novos-text-files/' . $databaseFile->file_name, $filesystemFiles);
            return [
                'fileName' => $databaseFile->file_name,
                'refName' => $databaseFile->ref_name,
                'file_created_at' => $databaseFile->created_at,
                'existsInFilesystem' => $existsInFilesystem
            ];
        });

        return NovosResponseFormatter::formatSuccess($mergedFiles->toArray(), 200);

    }


    public function createFile(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'fileName' => 'required|string|max:255',
                'content' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
    
           
            $filename = uniqid() . '_' . NovosHelpers::formatFileName($request->fileName) . '.txt';

            $file = new File();
            $file->file_name = $request->fileName;
            $file->ref_name = $filename;
            $file->save();
    
            Storage::disk('local')->put('novos-text-files/' . $filename, $request->content);
    
            return NovosResponseFormatter::formatSuccess(['message' => 'File Stored Successfully!'], 201);

        } catch (ValidationException $exception) {

            return NovosResponseFormatter::formatError(['message' => $exception->getMessage()], 422);

        } catch (\Exception $exception) {
            return NovosResponseFormatter::formatError(['message' => $exception->getMessage()], 500);
        }
    }

    public function getFile(Request $request, string $fileName): JsonResponse
    {
        
        try {
            $file = File::where('ref_name', $fileName)->first();
            $fileContents = Storage::get('novos-text-files/' . $fileName);
            return NovosResponseFormatter::formatSuccess(['file_name' => $file->file_name,'file_content' => $fileContents], 200);
        } catch (\Exception $e) {
            return NovosResponseFormatter::formatError(['message' => 'File not found', 'code' => 404],404);
        }
    }

    public function deleteFile(Request $request, string $fileName): JsonResponse
    {
        try {
            $file = File::where('ref_name', $fileName)->first();
            if (!$file) {
                return NovosResponseFormatter::formatError(['message' => 'File not found', 'code' => 404], 404);
            }
            $file->delete();
            Storage::delete('novos-text-files/' . $fileName);

            return NovosResponseFormatter::formatSuccess(['message' => 'File Deleted Successfully.'], 200);
        } catch (\Exception $e) {
          
            return NovosResponseFormatter::formatError(['message' => 'An error occurred while deleting the file', 'code' => 500], 500);
        }
    }



    public function getContents(Request $request): String
    {
        
        $files = Storage::files('novos-text-files/');
        $txtFiles = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
                $txtFiles[] = $file;
            }
        }

        if (empty($txtFiles)) {
            return '';
        }
        foreach ($txtFiles as $file) {
            $fileContents = Storage::get($file);
            $allFilesContents[] = $fileContents;
        }

       
        return implode(',',$allFilesContents);
    }
}
