<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function singleUpload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image|mimes:png,jpg,jpeg,svg',
        ]);

        if ($request->file('file')->isValid()) {

            $file = $request->file('file');
            $fileName = 'singleUpload-' . time() . '.' . $file->getClientOriginalExtension();

            $request->file('file')->move('image', $fileName);
        }

        return response(['status' => 'success']);
    }

    public function multiUpload(Request $request)
    {
        $this->validate($request, [
            'file.*' => 'required|image|mimes:png,jpg,jpeg,svg'
        ]);

        foreach ($request->file('file') as $image) {

            if ($image->isValid()) {

                $file = $image;
                $fileName = 'multiUpload-' . time() . '.' . $file->getClientOriginalExtension();

                $image->move('image', $fileName);
            }
            sleep(1);
        }

        return response(['status' => 'success']);
    }
}
