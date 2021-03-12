<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function singleUpload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image|mimes:png,jpg,jpeg,svg',
        ]);

        if ($request->file->isValid()) {

            $file = $request->file;
            $fileName = 'singleUpload-' . time() . '.' . $file->getClientOriginalExtension();

            $request->file->move('image', $fileName);
        }

        return $this->sendResponse('success', 'file uploaded', config('app.url') . '/image/' . $fileName, 200);
    }

    // Upload to imgbb
    public function multiUpload(Request $request)
    {
        $this->validate($request, [
            'file.*' => 'required|image|mimes:png,jpg,jpeg,svg'
        ]);

        $data = [];
        foreach ($request->file as $image) {

            if ($image->isValid()) {

                $file = base64_encode(file_get_contents($image));
                $fileName = 'multiUpload-' . time();

                $client = new Client();
                $response = $client->request('POST', 'https://api.imgbb.com/1/upload', [
                    'form_params' => [
                        'key' => 'f98a15c0e84720165f5cd99516022338',
                        'image' => $file,
                        'name' => $fileName
                    ]
                ]);
            }

            $res = json_decode($response->getBody()->getContents(), true);
            $data[] =  $res['data']['url'];
        }

        return $this->sendResponse('success', 'files uploaded', $data, 200);
    }
}
