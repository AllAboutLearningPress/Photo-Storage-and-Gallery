<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Arr;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Aws\Lambda\LambdaClient;
use Aws\Credentials\Credentials;
use PhpOption\None;

class IndexController extends Controller
{
    /**
     * Display a listing of photos in /.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $credentials = new Credentials(config('services.ses.key'), config('services.ses.secret'));
        $client = new LambdaClient(array(
            'credentials' => $credentials,
            'region' => config('services.ses.region'),
            'version' => 'latest'
        ));
        $result = $client->invoke(array(
            // FunctionName is required
            'FunctionName' => 'arn:aws:lambda:us-east-1:728758055541:function:photo_post_upload',
            'InvocationType' => 'RequestResponse',
            'LogType' => 'None',
            //'ClientContext' => 'string',
            'Payload' => json_encode(array(
                'photo' => '012c84847602494063e62b1a98e023c05064f333389d5465070d901a4c96408f.jpeg'
            )),
            //'Qualifier' => 'string',
        ));
        dd($result['Payload']->getContents());
        // $photos =  Photo::limit(5)->get();
        // //dd($photos);
        // return Inertia::render('Index', [
        //     'photos' => $this->add_temp_url($photos)
        // ]);

    }

    /**
     * Used to load more photos from requested offset
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function load_more(Request $request)
    {

        $data = $request->validate([
            'offset' => "required|integer"
        ]);

        $photos =  Photo::offset($data['offset'])->limit(5)->get();

        return $this->add_temp_url($photos);
    }

    /**
     * @param Array $photos - The array of photos returned by Laravel eloquet
     * @return Array Returns the same array with added url parameter
     */
    public function add_temp_url($photos)
    {
        foreach ($photos as $photo) {
            $photo->url = Storage::disk('s3')->temporaryUrl(
                'full_size/' . $photo->file_name,
                now()->addMinutes(10)
            );
        }
        return $photos;
    }
}
