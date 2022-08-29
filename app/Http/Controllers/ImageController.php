<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCreateImageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(GetCreateImageRequest $request): JsonResponse
    {
        $user = $request->user();
        $name = $request->input('name');
        $file = $request->file('file');
        $fileName =  $file->hashName();
        Storage::disk('s3')->put($fileName,file_get_contents($file),['visibility'=>'public']);
        $fileUrl = Storage::disk('s3')->url($fileName);

        $image = $user->images()->create([
            'name' => $name,
            'url' => $fileUrl
        ]);

        return $this->response(200,Lang::get('messages/crud.create_image'),$image);
    }
}
