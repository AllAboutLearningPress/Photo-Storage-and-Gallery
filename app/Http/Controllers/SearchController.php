<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Utils\AwsV4;


class SearchController extends Controller
{
    public function searchTitle(Request $request)
    {
        $data = $request->validate(['search' => 'required|string']);
        $photos = Photo::search($data['search'])->get();
        //return '[{"id":22,"title":"maxresdefault.jpeg","slug":"maxresdefault","size":83055,"height":720,"width":1280,"file_type":"jpeg","user_id":1,"parent_id":null,"file_name":"48909b1589de926be6dcb906d5fcaba59fe023d1045c21d3cbc966cf9872dcfa.jpeg","location":null,"license":null,"should_process":0,"time_taken":null,"deleted_at":null,"created_at":"2021-07-11T15:56:18.000000Z","updated_at":"2021-07-11T15:56:26.000000Z","src":"https:\/\/aalpphotosdev.s3.ap-southeast-1.amazonaws.com\/thumbnails\/48909b1589de926be6dcb906d5fcaba59fe023d1045c21d3cbc966cf9872dcfa.jpeg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjENn%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEaDmFwLXNvdXRoZWFzdC0xIkYwRAIgDQqhsrMUIXFXAaTogj9dzx50fUuXU%2F1GIwO3h%2Fm9dRUCIBYqsRC2mNUL1JIEjPAMtxFEltw2Jaea4fIwQ3XgFr%2FLKo0ECML%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEQAhoMMzI3MTc2MzAzNjE1Igz8HBZV4aNSi2pPYjgq4QO6QVbRId0c5xa7uHEkFvRubgwixNbdpbmlA7NmXZtUVKDgR8535qkFNTZGlRPjAQU5tWOpUzOAPZpVTtR5JVeWd94LnRNg%2BV3L728tfOGUubCKIsn8vMOtzQVF%2BzKU3701TzwwtcJAuajVj%2FSOeOIqbIeCEz25ZSYbcf%2FlswtNrmOfDzwDNLMNClWVAsynGyO%2BIzltjZBAesbznUqlbCscozHdoepqR1JWAJZkEibiQfxujabyE0VCbQeZnxE8RcohZtumOlFTmSybnD0b7hYsm8WHkDIey4EdEVQnw1aQf9KuCGH9yBtnRZ9aDgBTuE3FoAWMUraZ8PpKu0jIXvv6UNbd9jquwnGuUJ667qsM1SzAe9ZDpFRegI9xl%2FuQJfn8q5%2B0pzjFxOWpKLN5FRaxuSGs7xGotIUvk10D7OO9YUlruEqqSN0Sw2pHJcjxA6fH6lNkV9xludiyAnc%2B%2FfO799jLQyFiQIbyrf6%2FkRW58BvYvsLUDOw%2BkIRmm893zPxHo%2FhKUs2tJWtLKHjzS0Zy7Hyi9EyKeqVDbL7ZMbUvhTZK2DYLlIor1sV5cWfox1dr8GzQ365OSu0ytY5dl98eUjJx1uQtTFKGE5ceTKz2Rk87uzDhUPINE4PVdYGMIaujMOq6rIcGOqYB8I5cITJd7cVphOWog80Iauiz0t9osViUvdxe8%2BUZEgHO7XziOniZXkejTY8H9BPi3lz9gAgq%2BYz9CPp2bP3YBAUYOuEl%2F1If9deijABNdEqibT%2BkS1T85K6D4Mhjcd63n4w8L33FCveGuL2OadNbiWy%2Bdb1oudAVeW55rv3U11YK%2B151B9mH9%2FT2ib91whBjDB3SHaH3ralV9B8OSKwR8f8XSE%2B7Nw%3D%3D&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIAUYLJ2GP7TPC343RQ%2F20210711%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20210711T172359Z&X-Amz-SignedHeaders=host&X-Amz-Expires=599&X-Amz-Signature=08338fb7438a373221a4a18962d890d150a24a06b01198b42d9a45d7f11edd17"}]';

        // foreach ($photos as $photo) {
        //     $photo->add_temp_url('thumbnails');
        // }
        //return $photos->toArray();
        $s3Client = new \Aws\S3\S3Client([
            'region' => 'ap-southeast-1', // config('filesystems.disks.s3_fullsize.region'),
            'version' => '2006-03-01',
        ]);
        $provider = \Aws\Credentials\CredentialProvider::defaultProvider();
        $creds = $provider()->wait();
        // $creds->key,secret
        //\Aws\Signature\Signature
        $awsV4 = new AwsV4($creds);
        for ($x = 0; $x < count($photos); $x++) {
            //$photos[$x]->add_temp_url('thumbnails');


        }
        return $photos->toArray();
    }
}
